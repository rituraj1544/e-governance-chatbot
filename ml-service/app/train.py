"""Train, compare and evaluate the explainable TF-IDF intent classifier."""
from __future__ import annotations

import json
from pathlib import Path

import joblib
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix, f1_score
from sklearn.model_selection import train_test_split

from app.preprocessing import preprocess_text

BASE_DIR = Path(__file__).resolve().parents[1]
DATA_PATH = BASE_DIR / "data" / "intents.json"
MODEL_DIR = BASE_DIR / "models"
RANDOM_STATE = 42


def load_examples(path: Path = DATA_PATH):
    payload = json.loads(path.read_text(encoding="utf-8"))
    texts = [item["text"] for item in payload["examples"]]
    labels = [item["intent"] for item in payload["examples"]]
    counts = {label: labels.count(label) for label in sorted(set(labels))}
    if not counts or min(counts.values()) < 30:
        raise ValueError(f"Each intent needs at least 30 examples; found {counts}")
    if len(set(texts)) != len(texts):
        raise ValueError("Training examples must be unique")
    return texts, labels, counts


def make_vectorizer(analyzer: str):
    if analyzer == "word":
        return TfidfVectorizer(
            preprocessor=preprocess_text,
            tokenizer=str.split,
            token_pattern=None,
            ngram_range=(1, 2),
            min_df=1,
            sublinear_tf=True,
        )
    return TfidfVectorizer(
        preprocessor=preprocess_text,
        analyzer="char_wb",
        ngram_range=(2, 5),
        min_df=1,
        sublinear_tf=True,
    )


def make_classifier():
    return LogisticRegression(
        max_iter=2000,
        class_weight="balanced",
        C=4.0,
        random_state=RANDOM_STATE,
    )


def threshold_for(validation_labels, probabilities, classes):
    raw_predictions = np.asarray(classes)[probabilities.argmax(axis=1)]
    confidence = probabilities.max(axis=1)
    candidates = np.arange(0.20, 0.901, 0.025)
    scored = []
    for threshold in candidates:
        predictions = np.where(confidence < threshold, "unknown", raw_predictions)
        scored.append((f1_score(validation_labels, predictions, average="macro", zero_division=0), threshold))
    best_score = max(score for score, _ in scored)
    best_threshold = max(threshold for score, threshold in scored if score == best_score)
    return float(best_threshold), float(best_score)


def metrics(labels, predictions, class_names):
    report = classification_report(labels, predictions, labels=class_names, output_dict=True, zero_division=0)
    return {
        "accuracy": round(float(accuracy_score(labels, predictions)), 4),
        "macro_precision": round(float(report["macro avg"]["precision"]), 4),
        "macro_recall": round(float(report["macro avg"]["recall"]), 4),
        "macro_f1": round(float(report["macro avg"]["f1-score"]), 4),
        "per_class": {
            name: {key: round(float(report[name][key]), 4) for key in ("precision", "recall", "f1-score", "support")}
            for name in class_names
        },
        "confusion_matrix": confusion_matrix(labels, predictions, labels=class_names).tolist(),
        "confusion_matrix_labels": class_names,
    }


def train_and_evaluate():
    texts, labels, counts = load_examples()
    x_train_all, x_test, y_train_all, y_test = train_test_split(
        texts, labels, test_size=0.20, random_state=RANDOM_STATE, stratify=labels
    )
    x_train, x_validation, y_train, y_validation = train_test_split(
        x_train_all, y_train_all, test_size=0.25, random_state=RANDOM_STATE, stratify=y_train_all
    )
    comparisons = {}
    candidates = {}
    for analyzer in ("word", "char"):
        vectorizer = make_vectorizer(analyzer)
        train_matrix = vectorizer.fit_transform(x_train)
        validation_matrix = vectorizer.transform(x_validation)
        classifier = make_classifier().fit(train_matrix, y_train)
        probabilities = classifier.predict_proba(validation_matrix)
        threshold, validation_f1 = threshold_for(y_validation, probabilities, classifier.classes_)
        predicted = classifier.classes_[probabilities.argmax(axis=1)]
        comparisons[analyzer] = {
            "validation_accuracy": round(float(accuracy_score(y_validation, predicted)), 4),
            "validation_macro_f1": round(validation_f1, 4),
            "threshold": round(threshold, 3),
        }
        candidates[analyzer] = (validation_f1, comparisons[analyzer]["validation_accuracy"], threshold)

    chosen = max(candidates, key=lambda name: (candidates[name][0], candidates[name][1]))
    chosen_threshold = candidates[chosen][2]
    final_vectorizer = make_vectorizer(chosen)
    train_matrix = final_vectorizer.fit_transform(x_train_all)
    final_classifier = make_classifier().fit(train_matrix, y_train_all)
    test_matrix = final_vectorizer.transform(x_test)
    test_probabilities = final_classifier.predict_proba(test_matrix)
    raw_test = final_classifier.classes_[test_probabilities.argmax(axis=1)]
    confidence = test_probabilities.max(axis=1)
    final_test = np.where(confidence < chosen_threshold, "unknown", raw_test)
    class_names = sorted(set(labels))
    report = {
        "examples_per_intent": counts,
        "total_examples": len(texts),
        "split_sizes": {"train": len(x_train), "validation": len(x_validation), "test": len(x_test)},
        "random_state": RANDOM_STATE,
        "validation_comparison": comparisons,
        "selected_analyzer": chosen,
        "confidence_threshold": round(float(chosen_threshold), 3),
        "test": metrics(y_test, final_test, class_names),
    }

    MODEL_DIR.mkdir(parents=True, exist_ok=True)
    joblib.dump(final_vectorizer, MODEL_DIR / "vectorizer.joblib")
    joblib.dump(final_classifier, MODEL_DIR / "classifier.joblib")
    (MODEL_DIR / "evaluation.json").write_text(json.dumps(report, ensure_ascii=False, indent=2), encoding="utf-8")
    return report


if __name__ == "__main__":
    print(json.dumps(train_and_evaluate(), ensure_ascii=False, indent=2))

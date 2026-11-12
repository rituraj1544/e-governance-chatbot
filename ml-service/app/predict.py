"""Inference helpers; the FastAPI app loads artifacts once at startup."""
from __future__ import annotations

import json
from pathlib import Path

import joblib

MODEL_DIR = Path(__file__).resolve().parents[1] / "models"


def load_artifacts(model_dir: Path = MODEL_DIR):
    vectorizer = joblib.load(model_dir / "vectorizer.joblib")
    classifier = joblib.load(model_dir / "classifier.joblib")
    threshold = float(json.loads((model_dir / "evaluation.json").read_text(encoding="utf-8"))["confidence_threshold"])
    return vectorizer, classifier, threshold


def predict(query: str, vectorizer, classifier, threshold: float) -> dict:
    probabilities = classifier.predict_proba(vectorizer.transform([query]))[0]
    index = int(probabilities.argmax())
    raw_intent = str(classifier.classes_[index])
    confidence = float(probabilities[index])
    confident = confidence >= threshold and raw_intent != "unknown"
    return {
        "intent": raw_intent if confident else "unknown",
        "confidence": round(confidence, 5),
        "is_confident": confident,
    }

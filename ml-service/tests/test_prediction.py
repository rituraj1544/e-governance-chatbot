from fastapi.testclient import TestClient

from app.main import app
from app.predict import load_artifacts, predict
from app.preprocessing import preprocess_text


def test_artifacts_load_and_expected_language_queries():
    vectorizer, classifier, threshold = load_artifacts()
    assert 0 < threshold < 1
    assert predict("How can I apply for a PAN card?", vectorizer, classifier, threshold)["intent"] == "pan_card"
    assert predict("मुझे आधार कार्ड की जानकारी चाहिए", vectorizer, classifier, threshold)["intent"] == "aadhaar"
    assert predict("PM Kisan ka status kaise check karun", vectorizer, classifier, threshold)["intent"] == "pm_kisan"


def test_unknown_and_text_preprocessing():
    vectorizer, classifier, threshold = load_artifacts()
    result = predict("What is the weather forecast for tomorrow?", vectorizer, classifier, threshold)
    assert result["intent"] == "unknown"
    assert result["is_confident"] is False
    assert preprocess_text("  आधार—Card!!! ") == "आधार card"


def test_confidence_threshold_forces_uncertain_scores_to_fallback():
    vectorizer, classifier, _ = load_artifacts()
    result = predict("How can I apply for a PAN card?", vectorizer, classifier, threshold=1.01)
    assert result["intent"] == "unknown"
    assert result["is_confident"] is False


def test_health_and_prediction_endpoints():
    with TestClient(app) as client:
        health = client.get("/health")
        assert health.status_code == 200
        assert health.json()["model_loaded"] is True
        result = client.post("/predict", json={"query": "Scholarship ki details chahiye"})
        assert result.status_code == 200
        assert result.json()["intent"] == "scholarship"


def test_invalid_query_is_rejected():
    with TestClient(app) as client:
        assert client.post("/predict", json={"query": ""}).status_code == 422
        assert client.post("/predict", json={"query": "x"}).status_code == 422


def test_prediction_returns_503_if_model_is_unavailable():
    with TestClient(app) as client:
        previous = (app.state.vectorizer, app.state.classifier, app.state.threshold)
        try:
            app.state.classifier = None
            assert client.get("/health").json()["model_loaded"] is False
            assert client.post("/predict", json={"query": "Aadhaar help"}).status_code == 503
        finally:
            app.state.vectorizer, app.state.classifier, app.state.threshold = previous

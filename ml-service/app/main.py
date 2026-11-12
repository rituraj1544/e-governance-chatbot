from contextlib import asynccontextmanager

from fastapi import FastAPI, HTTPException

from app.predict import load_artifacts, predict
from app.preprocessing import preprocess_text
from app.schemas import PredictRequest, PredictResponse


@asynccontextmanager
async def lifespan(app: FastAPI):
    try:
        app.state.vectorizer, app.state.classifier, app.state.threshold = load_artifacts()
        app.state.model_error = None
    except Exception as error:  # Keep health available so operators can see a missing model.
        app.state.vectorizer = app.state.classifier = app.state.threshold = None
        app.state.model_error = str(error)
    yield


app = FastAPI(title="SevaSetu Intent Service", version="1.0.0", lifespan=lifespan)


@app.get("/health")
def health():
    ready = getattr(app.state, "classifier", None) is not None
    result = {"status": "ok" if ready else "model_unavailable", "model_loaded": ready}
    if not ready:
        result["detail"] = getattr(app.state, "model_error", "Model artifacts have not been loaded")
    return result


@app.post("/predict", response_model=PredictResponse)
def classify(payload: PredictRequest):
    if getattr(app.state, "classifier", None) is None:
        raise HTTPException(status_code=503, detail="Intent model is not available")
    query = preprocess_text(payload.query)
    if len(query) < 2:
        raise HTTPException(status_code=422, detail="Query must contain at least two useful characters")
    return predict(query, app.state.vectorizer, app.state.classifier, app.state.threshold)

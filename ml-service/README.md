# Intent classification service

This local FastAPI service predicts a government-service intent. It does not generate chatbot answers; Laravel uses the prediction to retrieve content from MySQL.

From `ml-service/` in PowerShell:

```powershell
py -3.13 -m venv .venv
.\.venv\Scripts\python.exe -m pip install -r requirements.txt
.\.venv\Scripts\python.exe -m app.train
.\.venv\Scripts\python.exe -m uvicorn app.main:app --reload --host 127.0.0.1 --port 8001
```

The service compares word and character TF-IDF approaches using a stratified validation split, selects a confidence threshold using validation macro-F1, refits the chosen classifier, and writes a held-out test report in `models/evaluation.json`. `vectorizer.joblib` and `classifier.joblib` are loaded once when the API starts.

The checked-in 242-example model report compares validation macro-F1 of 0.5791 (word n-grams) and 0.7016 (character n-grams), and records 0.8776 accuracy / 0.8823 macro-F1 on its 49-example held-out test split. The selected validation threshold is 0.225. These values describe this small dataset only; they do not guarantee live prediction quality.

Endpoints:

- `GET /health` reports whether the model loaded.
- `POST /predict` accepts `{"query":"PM Kisan ka status kaise check karun?"}` and returns the predicted intent, confidence score, and threshold status.

If model artifacts are missing, health reports `model_unavailable` and prediction returns HTTP 503. Laravel handles that failure and retains its rule-based flow.

Run checks with ` .\.venv\Scripts\python.exe -m pytest` after training. Dataset examples live in `data/intents.json`.

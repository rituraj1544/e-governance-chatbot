FROM python:3.13-slim

WORKDIR /app

# Copy requirements first (for caching)
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY . .

# Train the model during build
RUN python -m app.train

EXPOSE 8001

# Start FastAPI
CMD uvicorn app.main:app --host 0.0.0.0 --port $PORT

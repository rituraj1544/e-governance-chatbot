from pydantic import BaseModel, Field


class PredictRequest(BaseModel):
    query: str = Field(min_length=2, max_length=2000)


class PredictResponse(BaseModel):
    intent: str
    confidence: float = Field(ge=0, le=1)
    is_confident: bool

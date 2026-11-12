import re
import unicodedata


_NON_TEXT = re.compile(r"[^a-z0-9\u0900-\u097f\s]", flags=re.IGNORECASE)
_WHITESPACE = re.compile(r"\s+")


def preprocess_text(text: str) -> str:
    """Normalize text while preserving Devanagari and Latin script terms."""
    normalized = unicodedata.normalize("NFKC", text or "").lower()
    normalized = _NON_TEXT.sub(" ", normalized)
    return _WHITESPACE.sub(" ", normalized).strip()

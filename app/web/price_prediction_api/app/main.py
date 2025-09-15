from fastapi import FastAPI

app = FastAPI(title="Sugarcane API")

@app.get("/api/health")
def health():
    return {"ok": True}

@app.get("/api/hello")
def hello(name: str = "world"):
    return {"message": f"Hello, {name}!"}
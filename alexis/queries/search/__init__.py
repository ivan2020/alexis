import os
from alexis.connection import db


__all__ = ["search"]


DIR=os.path.dirname(__file__)
REDUCE=open(os.path.join(DIR, "reduce.js")).read()
FINALIZE=open(os.path.join(DIR, "finalize.js")).read()


def search(condition, limit=3):
    return db["hotels"].group({"country": True}, condition, {"hotels": [], "limit": limit}, REDUCE, FINALIZE)

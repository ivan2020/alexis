import os
from alexis.connection import db


__all__ = ["hotel_packs"]


DIR=os.path.dirname(__file__)
REDUCE=open(os.path.join(DIR, "reduce.js")).read()
FINALIZE=open(os.path.join(DIR, "finalize.js")).read()


def hotel_packs():
    return db["hotels"].group(None, None, {"centers": [], "hotels": []}, REDUCE, FINALIZE)

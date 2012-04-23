import json
import bson


class JSONEncoder(json.JSONEncoder):
    def default(self, o):
        if isinstance(o, bson.ObjectId):
            return str(o)

        return super().default(o)


def dumps(obj):
    return json.dumps(obj, cls=JSONEncoder)

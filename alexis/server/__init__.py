import os
from bottle import get, post, run, request, response
import alexis.queries
import alexis.parser
import alexis.json


INDEX_HTML = open(os.path.join(os.path.dirname(__file__), "index.html"), encoding="utf-8").read()


@get("/")
def index():
    return INDEX_HTML


@post("/search-requests")
def post_search_request():
    query = request.forms.get("query")

    response.set_header("Content-Type", "application/json")

    if not query:
        response.status = "400 Bad Request"
        return alexis.json.dumps({"error": "Invalid query"})

    filters = alexis.parser.parse(query)
    result = alexis.queries.search(filters)

    if not filters:
        return alexis.json.dumps({"error": "Sorry, we can't recognize your query.", "countries": result})

    return alexis.json.dumps({"countries": result})


def main(**kwargs):
    run(**kwargs)

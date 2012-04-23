# coding=utf-8

PHRASES = [
    (("ai", "все включено"), {"features.тип питания": {"$exists": True, "$in": ["все включено", "ультра все включено"]}})
]


def parse(query):
    filters = {}

    for phrases, filter in PHRASES:
        for phrase in phrases:
            if phrase in query:
                filters.update(filter)

    return filters

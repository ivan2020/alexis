import urllib.request
import xml.etree.ElementTree
import datetime
import threading
from alexis.connection import db


CONFIG = {"token": None}
NULL_STAMP = "0x0000000000000000"
MIN_INT = -2147483647


samo_int = lambda x: int(x) if int(x) != MIN_INT else None
samo_date = lambda x: datetime.datetime.strptime(x, "%Y-%m-%dT%H:%M:%S")


REFERENCES = [
    ("state", db["samo.states"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]]
    ]),
    ("region", db["samo.regions"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["state", samo_int]
    ]),
    ("town", db["samo.towns"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["state", samo_int],
        ["region", samo_int]
    ]),
    ("star", db["samo.stars"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]]
    ]),
    ("hotel", db["samo.hotels"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["star", samo_int],
        ["town", samo_int]
    ]),
    ("room", db["samo.rooms"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]]
    ]),
    ("htplace", db["samo.places"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["pcount", samo_int],
        ["adult", samo_int],
        ["child", samo_int],
        ["infant", samo_int],
        ["age1min", float],
        ["age1max", float],
        ["age2min", float],
        ["age2max", float],
        ["age3min", float],
        ["age3max", float]
    ]),
    ("meal", db["samo.meals"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]]
    ]),
    ("class", db["samo.flight_classes"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["alias"]
    ]),
    ("port", db["samo.airports"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["alias"],
        ["town", samo_int]
    ]),
    ("freight", db["samo.freights"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["trantype", samo_int],
        ["source", samo_int],
        [["srcport", "source-airport"], samo_int],
        [["target", "destination"], samo_int],
        [["trgport", "destination-airport"], samo_int]
    ]),
    ("servtype", db["samo.service_types"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]]
    ]),
    ("service", db["samo.services"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        [["servtype", "service-type"], samo_int]
    ]),
    ("insure", db["samo.insurance_types"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["state", samo_int]
    ]),
    ("visapr", db["samo.visa_types"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["state", samo_int]
    ]),
    ("currency", db["samo.currencies"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]]
    ]),
    ("tour", db["samo.tours"], [
        [["name", "name-ru"]],
        [["lname", "name-en"]],
        ["state", samo_int],
        [["townfrom", "town"], samo_int]
    ]),
    ("spog", db["samo.spo"], [
        [["fullnumber", "number"]],
        ["tour", samo_int],
        [["spodate", "date"], samo_date],
        [["datebeg", "begin"], samo_date],
        [["dateend", "end"], samo_date],
        [["rqdatebeg", "rq_begin"], samo_date],
        [["rqdateend", "rq_end"], samo_date],
        ["note"]
    ]),
    ("frblock", db["samo.freight_blocks"], [
        ["date", samo_date],
        ["freight", samo_int],
        ["class", samo_int],
        [["rcount", "status"]]
    ]),
    ("stopsale", db["samo.stopsale"], [
        [["datebeg", "begin"], samo_date],
        [["dateend", "end"], samo_date],
        ["hotel", samo_int],
        ["room", samo_int],
        [["htplace", "place"], samo_int],
        ["meal", samo_int],
        ["checkin", samo_int],
        ["nights", samo_int],
        [["spog", "spo"], samo_int],
        [["townfrom", "town"], samo_int]
    ]),
    ("freighttime", db["samo.freights_schedule"], [
        ["freight", samo_int],
        ["source", samo_int],
        [["srcport", "source-airport"], samo_int],
        [["target", "destination"], samo_int],
        [["trgport", "destination-airport"], samo_int],
        ["trantype", samo_int]
    ])
]


def generate_url(type, last_stamp=None, del_stamp=None):
    url = "http://agency.pegast.ru/samo5/export/default.php?samo_action=reference&oauth_token={0}&type={1}".format(CONFIG["token"], type)

    if last_stamp:
        url += "&laststamp="
        url += last_stamp

    if del_stamp:
        url += "&delstamp="
        url += del_stamp

    return url


def save_stamp(name, stamp):
    db["samo.stamps"].save({"_id": name, "stamp": stamp})


def load_stamp(name):
    stamp = db["samo.stamps"].find_one({"_id": name})
    return stamp["stamp"] if stamp else NULL_STAMP


def current_stamp():
    url = generate_url("currentstamp")
    doc = xml.etree.ElementTree.parse(urllib.request.urlopen(url))
    return doc.find("Data/currentstamp").get("stamp")


def reference_worker_factory(del_stamp, type, collection, getter_rules):
    def getter(item):
        result = {}

        for rule in getter_rules:
            if isinstance(rule[0], str):
                source_tag = rule[0]
                destination_tag = rule[0]
            else:
                source_tag, destination_tag = rule[0]

            value = item.get(source_tag)

            if value is None:
                continue

            if len(rule) > 1:
                value = rule[1](value)

            if value is None:
                continue

            result[destination_tag] = value

        return result

    def worker():
        stamp = load_stamp(type)

        url = generate_url(type, stamp, del_stamp)
        doc = xml.etree.ElementTree.parse(urllib.request.urlopen(url))

        collection.ensure_index("inc", unique=True)

        for_update = []
        for_remove = []

        for item in doc.find("Data"):
            inc = int(item.get("inc"))
            if item.get("status") == "D":
                for_remove.append(inc)
            else:
                if inc != MIN_INT:
                    for_update.append((inc, getter(item)))
                    stamp = item.get("stamp")

        for inc, set in for_update:
            collection.update({"inc": inc}, {
                "$set": set
            }, upsert=True)

        for inc in for_remove:
            collection.remove({"inc": inc})

        save_stamp(type, stamp)

    return worker


def directions_worker_factory(collection, temp_collection):
    def worker():
        url = generate_url("townstate")
        doc = xml.etree.ElementTree.parse(urllib.request.urlopen(url))

        collection.drop()

        for i in doc.find("Data"):
            temp_collection.insert({"town": int(i.get("town")), "state": int(i.get("state"))})

        collection.drop()
        temp_collection.rename(collection.name)

    return worker


def main(token):
    CONFIG["token"] = token

    del_stamp = load_stamp("del")

    if del_stamp == NULL_STAMP:
        del_stamp = current_stamp()

    threads = []

    for reference in REFERENCES:
        thread = threading.Thread(target=reference_worker_factory(del_stamp, *reference))
        thread.start()
        threads.append(thread)

    thread = threading.Thread(target=directions_worker_factory(db["samo.directions"], db["samo.directions_temp"]))
    thread.start()
    threads.append(thread)

    for thread in threads:
        thread.join()

    save_stamp("del", del_stamp)

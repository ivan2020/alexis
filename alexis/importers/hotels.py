import urllib.request
import xml.etree.ElementTree
import html.parser
import threading
import queue
import blanche
from alexis.connection import db


def hotel_parser_worker_factory(company_queue, collection, id_mapping):
    def worker():
        while True:
            company_element = company_queue.get()

            company = {"name-ru": company_element.find("name-ru").text.strip()}

            print("START:", company["name-ru"])

            element = company_element.find("description")
            if element is not None:
                company["description"] = html.parser.HTMLParser().unescape(element.text.replace("&nbsp;", " ")).strip()

            for tag in ["company-id", "country", "admn-area", "sub-admn-area", "locality-name", "street",
                        "email", "url", "sub-locality-name", "house-add", "address-add"]:
                element = company_element.find(tag)
                if element is not None:
                    company[tag] = element.text.strip()

            for tag in ["house", "km", "build"]:
                element = company_element.find(tag)
                if element is not None:
                    company[tag] = int(element.text)

            company["coordinates"] = {
                "lon": float(company_element.find("coordinates/lon").text),
                "lat": float(company_element.find("coordinates/lat").text)
            }

            company["phones"] = []
            for phone in company_element.findall("phone"):
                company["phones"].append({
                    "number": phone.find("number").text,
                    "type": phone.find("type").text
                })

            company["photos"] = []
            for photo_root in company_element.findall("photos/photo"):
                photo = {
                    "url": photo_root.get("url"),
                    "alt": photo_root.get("alt").strip(),
                    "type": photo_root.get("type")
                }

                good = False
                for i in range(10):
                    try:
                        photo["url"] = blanche.download(photo["url"])
                        good = True
                        break
                    except Exception as e:
                        print(e, company["name-ru"], photo["url"])

                if good:
                    company["photos"].append(photo)

            company["features"] = {}
            for feature in company_element.findall("feature-boolean"):
                company["features"][feature.get("name")] = bool(feature.get("value"))

            for feature in company_element.findall("feature-single"):
                company["features"][feature.get("name")] = feature.text.strip()

            for feature in company_element.findall("feature-enum-single"):
                company["features"][feature.get("name")] = feature.get("value")

            for feature in company_element.findall("feature-enum-multiple"):
                name = feature.get("name")

                if not name in company["features"]:
                    company["features"][name] = []

                company["features"][name].append(feature.get("value"))

            if company["company-id"] in id_mapping:
                company["_id"] = id_mapping[company["company-id"]]

            collection.save(company, safe=True)

            print("DONE:", company["name-ru"])

            company_queue.task_done()

    return worker


def main(workers=10):
    collection = db["hotels"]
    collection.ensure_index("company-id", unique=True)

    id_mapping = dict([(i["company-id"], i["_id"]) for i in collection.find(fields=["company-id"])])

    company_queue = queue.Queue()

    for i in range(workers):
        t = threading.Thread(target=hotel_parser_worker_factory(company_queue, collection, id_mapping))
        t.daemon = True
        t.start()

    companies_element = xml.etree.ElementTree.parse(urllib.request.urlopen("http://hotels.pegast.su/xml"))

    for company_element in companies_element.findall("company"):
        company_queue.put(company_element)

    company_queue.join()

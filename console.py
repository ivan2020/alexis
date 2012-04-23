#!/usr/bin/env python3
import argparse


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("action", choices=["serve", "hotels", "samo"])
    namespace, args = parser.parse_known_args()

    if namespace.action == "serve":
        parser.add_argument("--server", default="wsgiref")
        parser.add_argument("--host", default="127.0.0.1")
        parser.add_argument("--port", type=int, default=8080)

        kwargs = vars(parser.parse_args())
        from alexis.server import main as action

    elif namespace.action == "hotels":
        parser.add_argument("--workers", type=int, default=10)

        kwargs = vars(parser.parse_args())
        from alexis.importers.hotels import main as action

    elif namespace.action == "samo":
        parser.add_argument("token")

        kwargs = vars(parser.parse_args())
        from alexis.importers.samo import main as action

    else:
        return

    del kwargs["action"]
    action(**kwargs)


if __name__ == "__main__":
    main()

function finalize(out) {
    out.hotels.sort(function (a, b) {
        return a.popularity - b.popularity;
    });

    out.name = out.country;
    delete out.country;
    delete out.limit;
}

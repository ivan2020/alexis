function finalize(out) {
    out.result = [];

    for (var i = 0; i < out.centers.length; i++) {
        var center = out.centers[i];
        var hotels = out.hotels[i];

        hotels.sort(function (a, b) {
            return a[1] - b[1];
        });

        out.result.push({
            "coordinates": center,
            "hotels": hotels
        })
    }

    delete out.centers;
    delete out.hotels;
}

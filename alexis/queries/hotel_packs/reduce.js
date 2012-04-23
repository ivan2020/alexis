function reduce(obj, prev) {
    var dists = [], i, dist;

    for (i = 0; i < prev.centers.length; i++) {
        var center = prev.centers[i];

        dist = calculateTheDistance(obj.coordinates.lat, obj.coordinates.lon, center.lat, center.lon);

        // found registered center
        if (dist < 10000) {
            dists.push([i, dist]);
        }
    }

    if (dists.length == 0) {
        // register new center
        prev.centers.push(obj.coordinates);
        prev.hotels.push([[obj._id, -1]]);

    } else {
        // add hotel to nearest center
        dists.sort(function (a, b) {
            return a[1] - b[1];
        });

        i = dists[0][0];
        dist = dists[0][1];

        prev.hotels[i].push([obj._id, dist])
    }
}

var EARTH_RADIUS = 6372795;

var calculateTheDistance = function (lat1, lon1, lat2, lon2) {
    lat1 = lat1 * Math.PI / 180;
    lat2 = lat2 * Math.PI / 180;
    lon1 = lon1 * Math.PI / 180;
    lon2 = lon2 * Math.PI / 180;

    var cl1 = Math.cos(lat1);
    var cl2 = Math.cos(lat2);
    var sl1 = Math.sin(lat1);
    var sl2 = Math.sin(lat2);
    var delta = lon2 - lon1;
    var cdelta = Math.cos(delta);
    var sdelta = Math.sin(delta);

    var y = Math.sqrt(Math.pow(cl2 * sdelta, 2) + Math.pow(cl1 * sl2 - sl1 * cl2 * cdelta, 2));
    var x = sl1 * sl2 + cl1 * cl2 * cdelta;

    return Math.round(Math.atan2(y, x) * EARTH_RADIUS);
};

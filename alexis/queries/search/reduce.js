function reduce(obj, prev) {
    if (prev.hotels.length < prev.limit) {
        prev.hotels.push(obj);
    }
}

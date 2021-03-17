
export function getBordersOfBordersUuids(borders, focusedProvince) {
    let uuidsToIgnore = borders.map(border => border.uuid);
    if (focusedProvince) {
        uuidsToIgnore.push(focusedProvince.uuid);
    }
    let uuids = [];
    borders.forEach(function (border) {
        let filteredUuids = border.borderUuids.filter(function (uuid) {
            return ! uuidsToIgnore.includes(uuid) && ! uuids.includes(uuid);
        })
        uuids.push(...filteredUuids);
    })
    return uuids;
}

const ROUTE_PREFIX = '/api/v1/province-events/';

export async function getProvinceEvent(uuid) {
    let response = await axios.get(ROUTE_PREFIX + uuid);
    return response.data;
}
export async function getGlobalEvents() {
    let response = await axios.get(ROUTE_PREFIX);
    return response.data;
}



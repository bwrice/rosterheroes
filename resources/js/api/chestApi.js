
const ROUTE_PREFIX = '/api/v1/chests/';

export async function open(chestUuid) {
    let response = await axios.post(ROUTE_PREFIX + chestUuid + '/open');
    return response.data;
}

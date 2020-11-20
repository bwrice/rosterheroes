const ROUTE_PREFIX = '/api/v1/side-quest-results/';

export async function getBattleground(resultUuid) {
    let response = await axios.get(ROUTE_PREFIX + resultUuid + '/battleground');
    return response.data;
}

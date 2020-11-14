const ROUTE_PREFIX = '/api/v1/campaigns/';

export async function campaignStops(campaignUuid) {
    let response = await axios.get(ROUTE_PREFIX + campaignUuid + '/campaign-stops');
    return response.data;
}

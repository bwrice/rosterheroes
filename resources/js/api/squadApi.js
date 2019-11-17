
const ROUTE_PREFIX = '';

export async function getSquad(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug);
    return response.data;
}

export async function getHeroes(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/heroes');
    return response.data;
}

export async function fastTravel(squadSlug, provinces) {
    let response = await axios.post('/api/v1/squads/' + squadSlug + '/fast-travel', {
        travelRoute: provinces
    });
    return response.data;
}

export async function getMobileStorage(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/mobile-storage');
    return response.data.data;
}

export async function getCurrentCampaign(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/current-campaign');
    return response.data.data;
}

export async function getSpellLibrary(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/spells');
    return response.data;
}

export async function getCurrentLocationProvince(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/current-location/province');
    return response.data;
}

export async function getCurrentLocationQuests(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/current-location/quests');
    return response.data;
}

export async function getBorderTravelCost(squadSlug, borderSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/border-travel/' + borderSlug);
    return response.data;
}

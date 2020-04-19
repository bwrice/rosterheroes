
const ROUTE_PREFIX = '/api/v1/squads/';

export async function getSquad(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug);
    return response.data;
}

export async function getHeroes(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/heroes');
    return response.data;
}

export async function fastTravel(squadSlug, provinces) {
    let response = await axios.post(ROUTE_PREFIX + squadSlug + '/fast-travel', {
        travelRoute: provinces
    });
    return response.data;
}

export async function getMobileStorage(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/mobile-storage');
    return response.data;
}

export async function getCurrentCampaign(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/current-campaign');
    return response.data;
}

export async function getSpellLibrary(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/spells');
    return response.data;
}

export async function getCurrentLocationProvince(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/current-location/province');
    return response.data;
}

export async function getCurrentLocationQuests(squadSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/current-location/quests');
    return response.data;
}

export async function getBorderTravelCost(squadSlug, borderSlug) {
    let response = await axios.get(ROUTE_PREFIX + squadSlug + '/border-travel/' + borderSlug);
    return response.data;
}

export async function joinQuest(squadSlug, questUuid) {
    let response = await axios.post(ROUTE_PREFIX + squadSlug + '/quests', {
        quest: questUuid
    });
    return response.data;
}

export async function leaveQuest(squadSlug, questUuid) {
    let response = await axios.delete(ROUTE_PREFIX + squadSlug + '/quests', {
        data: {
            quest: questUuid
        }
    });
    return response.data;
}

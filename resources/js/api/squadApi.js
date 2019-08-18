

export async function getSquad(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug);
    return response.data.data;
}

export async function getBarracksHeroes(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/barracks/heroes');
    return response.data.data;
}

export async function getRosterHeroes(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/roster/heroes');
    return response.data.data;
}

export async function getCurrentLocation(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/current-location');
    return response.data.data;
}

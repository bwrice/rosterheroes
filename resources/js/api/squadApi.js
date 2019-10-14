

export async function getSquad(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug);
    return response.data;
}

export async function getHeroes(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/heroes');
    return response.data;
}

// export async function getRosterHeroes(squadSlug) {
//     let response = await axios.get('/api/v1/squads/' + squadSlug + '/roster/heroes');
//     return response.data.data;
// }

export async function getCurrentLocation(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/current-location');
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

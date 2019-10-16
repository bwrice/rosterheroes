
export async function getHeroClasses() {
    let response = await axios.get('/api/v1/hero-classes');
    return response.data;
}

export async function getHeroRaces() {
    let response = await axios.get('/api/v1/hero-races');
    return response.data;
}

export async function getMeasurableTypes() {
    let response = await axios.get('/api/v1/measurable-types');
    return response.data;
}

export async function getCombatPositions() {
    let response = await axios.get('/api/v1/combat-positions');
    return response.data;
}

export async function getPositions() {
    let response = await axios.get('/api/v1/positions');
    return response.data;
}

export async function getTeams() {
    let response = await axios.get('/api/v1/teams');
    return response.data;
}

export async function getSports() {
    let response = await axios.get('/api/v1/sports');
    return response.data;
}

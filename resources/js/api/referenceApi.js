const ROUTE_PREFIX = '/api/v1/';


export async function getHeroClasses() {
    let response = await axios.get(ROUTE_PREFIX + 'hero-classes');
    return response.data;
}

export async function getHeroRaces() {
    let response = await axios.get(ROUTE_PREFIX + 'hero-races');
    return response.data;
}

export async function getMeasurableTypes() {
    let response = await axios.get(ROUTE_PREFIX + 'measurable-types');
    return response.data;
}

export async function getCombatPositions() {
    let response = await axios.get(ROUTE_PREFIX + 'combat-positions');
    return response.data;
}

export async function getPositions() {
    let response = await axios.get(ROUTE_PREFIX + 'positions');
    return response.data;
}

export async function getTeams() {
    let response = await axios.get(ROUTE_PREFIX + 'teams');
    return response.data;
}

export async function getSports() {
    let response = await axios.get(ROUTE_PREFIX + 'sports');
    return response.data;
}

export async function getLeagues() {
    let response = await axios.get(ROUTE_PREFIX + 'leagues');
    return response.data;
}

export async function getStatTypes() {
    let response = await axios.get(ROUTE_PREFIX + 'stat-types');
    return response.data;
}

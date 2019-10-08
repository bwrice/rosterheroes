
export async function getHeroClasses() {
    let response = await axios.get('/api/v1/hero-classes');
    return response.data;
}

export async function getHeroRaces() {
    let response = await axios.get('/api/v1/hero-races');
    return response.data;
}

export async function getCombatPositions() {
    let response = await axios.get('/api/v1/combat-positions');
    return response.data;
}

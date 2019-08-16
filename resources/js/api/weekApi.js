
export async function getCurrentWeek() {
    let response = await axios.get('/api/v1/weeks/current');
    return response.data.data;
}

export async function getCurrentPlayerSpirits() {
    let response = await axios.get('/api/v1/weeks/current/player-spirits');
    return response.data.data;
}


export async function getCurrentWeek() {
    let response = await axios.get('/api/v1/weeks/current');
    return response.data;
}

export async function getPlayerSpirits(weekUuid = 'current') {
    let response = await axios.get('/api/v1/weeks/' + weekUuid + '/player-spirits');
    return response.data;
}

export async function getGames(weekUuid = 'current') {
    let response = await axios.get('/api/v1/weeks/' + weekUuid + '/games');
    return response.data;
}

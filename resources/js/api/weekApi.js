
export async function getCurrentWeek() {
    let response = await axios.get('/api/v1/weeks/current');
    return response.data;
}

export async function getPlayerSpirits(weekUuid = 'current', offset = 0, limit = 100) {
    let response = await axios.get('/api/v1/weeks/' + weekUuid + '/player-spirits', {
        params: {
            offset,
            limit
        }
    });
    return response.data;
}

export async function getGames(weekUuid = 'current') {
    let response = await axios.get('/api/v1/weeks/' + weekUuid + '/games');
    return response.data;
}

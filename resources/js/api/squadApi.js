
export async function getBarracksHeroes(squadSlug) {
    let response = await axios.get('/api/v1/squads/' + squadSlug + '/barracks/heroes');
    return response.data.data;
}

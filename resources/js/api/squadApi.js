
export async function getBarracksHeroes(squadSlug) {
    return await axios.get('/api/v1/squads/' + squadSlug + '/barracks/heroes');
}

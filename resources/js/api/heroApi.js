
export async function addSpirit(heroSlug, spiritUuid) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/player-spirit/' + spiritUuid);
    return response.data.data;
}

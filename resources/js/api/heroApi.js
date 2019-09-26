
export async function addSpirit(heroSlug, spiritUuid) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/player-spirit/' + spiritUuid);
    return response.data.data;
}

export async function removeSpirit(heroSlug, spiritUuid) {
    let response = await axios.delete('/api/v1/heroes/' + heroSlug + '/player-spirit/' + spiritUuid);
    return response.data.data;
}

export async function emptySlot(heroSlug, slotUuid) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/empty-slots', {
        slots: [
            slotUuid
        ]
    });
    return response.data.data;
}

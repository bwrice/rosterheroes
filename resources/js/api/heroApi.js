
export async function addSpirit(heroSlug, spiritUuid) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/player-spirit', {
        spirit: spiritUuid
    });
    return response.data;
}

export async function removeSpirit(heroSlug, spiritUuid) {
    let response = await axios.delete('/api/v1/heroes/' + heroSlug + '/player-spirit/' + spiritUuid);
    return response.data;
}

export async function changeCombatPosition(heroSlug, combatPositionID) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/combat-position', {
        position: combatPositionID
    });
    return response.data;
}

export async function emptySlot(heroSlug, slotUuid) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/empty-slot', {
        slot: slotUuid
    });
    return response.data.data;
}

export async function equipFromWagon({heroSlug, slotUuid, itemUuid}) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/equip', {
        slot: slotUuid,
        item: itemUuid
    });
    return response.data.data;
}

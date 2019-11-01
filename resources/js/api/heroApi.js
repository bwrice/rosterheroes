
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
    return response.data;
}

export async function equipFromWagon(heroSlug, itemUuid) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/equip', {
        item: itemUuid
    });
    return response.data;
}

export async function getCostToRaiseMeasurable(heroSlug, measurableType, raiseAmount) {
    let response = await axios.get('/api/v1/heroes/' + heroSlug + '/raise-measurable', {
        params: {
            type: measurableType,
            amount: raiseAmount
        }
    });
    return response.data;
}

export async function raiseMeasurable(heroSlug, measurableType, raiseAmount) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/raise-measurable', {
        type: measurableType,
        amount: raiseAmount
    });
    return response.data;
}

export async function castSpell(heroSlug, spellID) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/cast-spell', {
        spell: spellID
    });
    return response.data;
}

export async function removeSpell(heroSlug, spellID) {
    let response = await axios.post('/api/v1/heroes/' + heroSlug + '/remove-spell', {
        spell: spellID
    });
    return response.data;
}

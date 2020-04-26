
const ROUTE_PREFIX = '/api/v1/heroes/';

export async function getHero(heroSlug) {
    let response = await axios.get(ROUTE_PREFIX + heroSlug);
    return response.data;
}

export async function addSpirit(heroSlug, spiritUuid) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/player-spirit', {
        spirit: spiritUuid
    });
    return response.data;
}

export async function removeSpirit(heroSlug, spiritUuid) {
    let response = await axios.delete(ROUTE_PREFIX + heroSlug + '/player-spirit/' + spiritUuid);
    return response.data;
}

export async function changeCombatPosition(heroSlug, combatPositionID) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/combat-position', {
        position: combatPositionID
    });
    return response.data;
}

export async function unequipItem(heroSlug, itemUuid) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/unequip', {
        item: itemUuid
    });
    return response.data;
}

export async function equipFromWagon(heroSlug, itemUuid) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/equip', {
        item: itemUuid
    });
    return response.data;
}

export async function getCostToRaiseMeasurable(heroSlug, measurableType, raiseAmount) {
    let response = await axios.get(ROUTE_PREFIX + heroSlug + '/raise-measurable', {
        params: {
            type: measurableType,
            amount: raiseAmount
        }
    });
    return response.data;
}

export async function raiseMeasurable(heroSlug, measurableType, raiseAmount) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/raise-measurable', {
        type: measurableType,
        amount: raiseAmount
    });
    return response.data;
}

export async function castSpell(heroSlug, spellID) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/cast-spell', {
        spell: spellID
    });
    return response.data;
}

export async function removeSpell(heroSlug, spellID) {
    let response = await axios.post(ROUTE_PREFIX + heroSlug + '/remove-spell', {
        spell: spellID
    });
    return response.data;
}

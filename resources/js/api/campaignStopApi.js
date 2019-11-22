
export async function addSkirmish(campaignStopUuid, skirmishUuid) {
    let response = await axios.post('/api/v1/campaign-stops/' + campaignStopUuid + '/skirmishes', {
        skirmish: skirmishUuid
    });
    return response.data;
}

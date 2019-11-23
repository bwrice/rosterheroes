
export async function addSkirmish(campaignStopUuid, skirmishUuid) {
    let response = await axios.post('/api/v1/campaign-stops/' + campaignStopUuid + '/skirmishes', {
        skirmish: skirmishUuid
    });
    return response.data;
}

export async function leaveSkirmish(campaignStopUuid, skirmishUuid) {
    let response = await axios.delete('/api/v1/campaign-stops/' + campaignStopUuid + '/skirmishes', {
        // need to specify data in axios.delete
        data: {
            skirmish: skirmishUuid
        }
    });
    return response.data;
}

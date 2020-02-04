
export async function joinSideQuest(campaignStopUuid, sideQuestUuid) {
    let response = await axios.post('/api/v1/campaign-stops/' + campaignStopUuid + '/side-quests', {
        sideQuest: sideQuestUuid
    });
    return response.data;
}

export async function leaveSideQuest(campaignStopUuid, sideQuestUuid) {
    let response = await axios.delete('/api/v1/campaign-stops/' + campaignStopUuid + '/side-quests', {
        // need to specify data in axios.delete
        data: {
            sideQuest: sideQuestUuid
        }
    });
    return response.data;
}

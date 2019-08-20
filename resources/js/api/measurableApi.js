
export async function getCostToRaise(measurableUuid, raiseAmount) {
    let response = await axios.get('/api/v1/measurables/' + measurableUuid + '/raise?amount=' + raiseAmount);
    return response.data;
}

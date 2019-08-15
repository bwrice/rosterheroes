
export async function getCurrentWeek() {
    let response = await axios.get('/api/v1/weeks/current');
    return response.data.data;
}

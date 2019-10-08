
export async function getHeroClasses() {
    let response = await axios.get('/api/v1/hero-classes');
    return response.data;
}

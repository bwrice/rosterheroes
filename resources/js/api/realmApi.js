
export async function getProvinces() {
    let response = await axios.get('/api/v1/provinces');
    return response.data;
}

export async function getTerritories() {
    let response = await axios.get('/api/v1/territories');
    return response.data;
}

export async function getContinents() {
    let response = await axios.get('/api/v1/continents');
    return response.data;
}

export async function getMapProvince(provinceSlug) {
    let response = await axios.get('/api/v1/map/provinces/' + provinceSlug);
    return response.data;
}

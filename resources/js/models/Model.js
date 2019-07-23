import { Model as BaseModel } from 'vue-api-query'

import axios from 'axios';
// inject global axios instance as http client to Model
BaseModel.$http = axios;

export default class Model extends BaseModel {

    // define a base url for a REST API
    baseURL () {
        return process.env.MIX_VUE_API_QUERY_BASE_URL + '/api/v1';
    }

    // implement a default request method
    request (config) {
        return this.$http.request(config)
    }
}
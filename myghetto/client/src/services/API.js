import axios from 'axios'

const BACKEND = 'http://localhost:8081/';

export default () => {
    return axios.create({
        baseURL: BACKEND
    })
}

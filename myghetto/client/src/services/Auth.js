import API from '@/services/API';

export default {
    register(cred) {
        return API().post('register', cred)
    }
}

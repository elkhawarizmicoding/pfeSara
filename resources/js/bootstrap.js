import 'bootstrap';
import axios from 'axios';
// window.axios = axios;
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios = axios
axios.defaults.baseURL = '/api/';
window.axios.defaults.withCredentials = true;
axios.defaults.headers.common = {
    'Content-Type': "application/json",
    'Accept': "application/json",
    'X-Requested-With': "XMLHttpRequest",
}

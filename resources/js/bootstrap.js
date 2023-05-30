import 'bootstrap';
import axios from 'axios';
import store from "./store/index.js";

window.axios = axios
axios.defaults.baseURL = '/api/';
window.axios.defaults.withCredentials = true;
axios.defaults.headers.common = {
    'Content-Type': "application/json",
    'Accept': "application/json",
    'X-Requested-With': "XMLHttpRequest",
}
if(store.state.token != null){
    axios.defaults.headers.common.Authorization =  'Bearer ' + store.state.token;
}

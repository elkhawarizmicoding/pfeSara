import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate";

// Create a new store instance.
const store = createStore({
    plugins: [createPersistedState()],
    state: {
        authenticated: false,
        token: null,
        userInfo: null,
    },
    mutations: {
        setAuthentication(state, boolean){
            state.authenticated = boolean
        },
        setToken(state, token){
            state.token = token
        },
        setUserInfo(state, userInfo){
            state.userInfo = userInfo
        }
    },
})
export default store

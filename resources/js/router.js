import {createRouter, createWebHistory} from "vue-router";
import Login from "./auth/Login.vue";
import Dashboard from "./profile/Dashboard.vue";
import store from "./store/index.js";

const routers = [
    {
        path: '/auth/login',
        name: 'Login',
        component: Login,
        meta: {
            title: 'Connexion',
        },
        beforeEnter: async (to, from, next) => {
            const isLoggedIn = store.state.authenticated;
            if (isLoggedIn) {
                return next('/');
            }
            next();
        },
    },
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: {
            title: 'Tableau de bord',
            requiresAuth: true,
        },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routers
});
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.state.authenticated) {
            return next('/auth/login')
        }else{
            next()
        }
    }else{
        next()
    }
})
router.beforeEach((to, from, next) => {
    document.title = to.meta.title + ' | Simulation'
    next()
})

export default router


import './bootstrap';
import { createApp } from 'vue';
import Main from "./Main.vue";
import router from "./router"
import store from "./store/index.js";

import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi',
    },
    components,
    directives,
})

const app = createApp(Main);

app.use(store)
app.use(router)
app.use(vuetify)
app.mount('#app')

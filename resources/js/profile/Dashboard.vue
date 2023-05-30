<template>
    <v-layout>
        <div class="position-fixed custom-navbar">
            <v-container class="pa-2">
                <div class="d-flex justify-content-start">
                    <div class="d-flex flax-left justify-content-center align-items-center" style="width: 85%;gap: 20px">
                        <!-- Logo -->
                        <div class="d-flex justify-content-center align-items-center" style="gap: 10px">
                            <v-img
                                src="../images/logo.png"
                                width="60"
                                class="m-auto"
                            ></v-img>
                            <div class="custom-text-logo">
                                <strong style="display: block;">ISTW</strong>
                                <span class="span-logo">Système web</span>
                            </div>
                        </div>
                        <!-- Search -->
                        <v-form class="position-relative w-100 h-75" @submit.prevent="search">
                            <v-btn
                                type="submit"
                                flat
                                class="position-absolute pin-search"
                                icon="mdi-magnify"
                                :loading="form.loading"
                            ></v-btn>
                            <div class="dropdown position-absolute custom-dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{dropdown_title}}
                                </button>
                                <ul class="dropdown-menu">
                                    <li @click="onSelectMode('classic_search')"><a class="dropdown-item" :class="form.mode === 'classic_search' ? 'active': ''">Recherche classique</a></li>
                                    <li @click="onSelectMode('personalized_search')"><a class="dropdown-item" :class="form.mode === 'personalized_search' ? 'active': ''">Recherche personnalisé</a></li>
                                </ul>
                            </div>
                            <input class="form-control custom-input h-100" v-model="form.query" placeholder="Entrez la recherche">
                        </v-form>
                    </div>
                    <!-- Account -->
                    <v-list class="ml-auto">
                        <v-list-item
                            prepend-avatar="../images/avatar.png"
                            :title="$store.state.userInfo.full_name"
                            :subtitle="$store.state.userInfo.email"
                        ></v-list-item>
                    </v-list>
                </div>
            </v-container>
        </div>
        <div class="w-100 position-relative" style="height: calc(100vh - 5.5em); margin-top: 5.5em">
            <div class="position-absolute cover-loading h-100 w-100 d-grid justify-content-center align-items-center" v-if="form.loading">
                <div class="text-center d-grid" style="gap: 5px">
                    <div class="lds-ripple ma-auto">
                        <div></div>
                        <div></div>
                    </div>
                    <span class="text-loading text-white">Attendre la réception des données ...</span>
                </div>
            </div>
            <v-container>
                <Classic class="mt-3" :search_response.sync="search_response_classic" v-if="form.mode === 'classic_search' && search_response_classic.length > 0"></Classic>
                <Personalized class="mt-3" :search_response.sync="search_response_personalized" v-else-if="form.mode === 'personalized_search' && search_response_personalized !== null"></Personalized>
                <welcome v-else class="position-absolute custom-welcome"></welcome>
            </v-container>
        </div>
    </v-layout>
</template>

<script>
import Welcome from "../components/Welcome.vue";
import Classic from "../components/search/Classic.vue";
import Personalized from "../components/search/Personalized.vue";

export default {
    name: "Dashboard",
    computed: {},
    components: {Personalized, Classic, Welcome},
    data(){
        return {
            form: {
                query: '',
                mode: 'classic_search',
                loading: false,
                error: false,
            },
            dropdown_title: 'Recherche classique',
            search_response_classic: [],
            search_response_personalized: null,
            //
        }
    },
    methods: {
        onSelectMode(mode){
            this.form.mode = mode;
            if(this.form.mode === 'classic_search') {
                this.dropdown_title = 'Recherche classique';
            }else if(this.form.mode === 'personalized_search'){
                this.dropdown_title = 'Recherche personnalisé';
            }
        },
        async search(){
            if(this.form.query == null || this.form.query.length === 0){
                return;
            }
            this.form.loading = true
            this.form.error = false
            await axios.get(`search/${this.form.mode}/${this.form.query}`).then(res => {
                const response = res.data;
                if(response.status){
                    if(this.form.mode === 'classic_search'){
                        this.search_response_classic = [];
                        response.data.forEach((item, index) => {
                            this.search_response_classic.push({
                                title: item.title,
                                description: item.snippet,
                                url: item.link,
                            });
                        });
                    }else if(this.form.mode === 'personalized_search'){
                        this.search_response_personalized = response;
                        console.log(this.search_response_personalized)
                    }
                }else{
                    this.form.error = true;
                }
            }).catch((error) => {
                this.form.error = true;
            })
            this.form.loading = false
        }
    }
}
</script>

<style scoped>

</style>

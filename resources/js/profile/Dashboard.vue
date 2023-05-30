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
                            <v-icon class="position-absolute pin-search" @click="search">mdi-magnify</v-icon>
                            <div class="dropdown position-absolute custom-dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Recherche classique
                                </button>
                                <ul class="dropdown-menu">
                                    <li @click="onSelectMode('classic_search')"><a class="dropdown-item active">Recherche classique</a></li>
                                    <li @click="onSelectMode('personalized_search')"><a class="dropdown-item">Recherche personnalisé</a></li>
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
            <Classic :search_response.sync="search_response" v-if="form.mode === 'classic_search' && search_response.length > 0"></Classic>
            <welcome v-else class="position-absolute custom-welcome"></welcome>
        </div>
    </v-layout>
</template>

<script>
import Welcome from "../components/Welcome.vue";
import Classic from "../components/search/Classic.vue";

export default {
    name: "Dashboard",
    components: {Classic, Welcome},
    data(){
        return {
            form: {
                query: '',
                mode: 'classic_search',
                loading: false,
                error: false,
            },
            search_response: [],
        }
    },
    methods: {
        onSelectMode(mode){
            this.form.mode = mode;
        },
        async search(){
            this.form.loading = true
            this.form.error = false
            await axios.get(`search/${this.form.mode}/${this.form.query}`).then(res => {
                const response = res.data;
                if(response.status){
                    console.log(response.data);
                    response.data.forEach((item, index) => {
                        this.search_response.push({
                            title: item.title,
                            description: item.description,
                            url: item.url,
                        });
                    });
                    console.log(this.search_response);
                }else{
                    this.form.error = true;
                }
            }).catch((error) => {
                this.form.error = true;
            })
            this.loading = false
            console.log(this.form);
        }
    }
}
</script>

<style scoped>

</style>

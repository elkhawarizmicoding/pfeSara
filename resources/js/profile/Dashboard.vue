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
                    <v-menu class="custom-menu">
                        <template v-slot:activator="{ props }">
                            <v-list
                                class="ml-auto"
                                v-bind="props"
                                style="cursor: pointer !important;"
                            >
                                <v-list-item
                                    prepend-avatar="../images/avatar.png"
                                    :title="$store.state.userInfo.full_name"
                                    :subtitle="$store.state.userInfo.email"
                                ></v-list-item>
                            </v-list>
                        </template>
                        <v-list>
                            <v-list-item @click="dialog_profile = true">
                                <template v-slot:prepend>
                                    <v-icon icon="mdi-account-edit"></v-icon>
                                </template>
                                <v-list-item-title>Modifier le profil</v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="logout()">
                                <template v-slot:prepend>
                                    <v-icon icon="mdi-logout-variant"></v-icon>
                                </template>
                                <v-list-item-title>Se déconnecter</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
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
                <Personalized class="mt-3" :search_response.sync="search_response_personalized" v-else-if="form.mode === 'personalized_search' && search_response_personalized !== null && show_data"></Personalized>
                <welcome v-else class="position-absolute custom-welcome"></welcome>
            </v-container>
        </div>
        <!-- Select La Base -->
        <v-dialog
            v-model="dialog"
            width="auto"
            persistent
        >
            <v-card>
                <v-card-text>
                    <v-card
                        class="custom-border-selected"
                        :disabled="!search_response_personalized.selected_term"
                    >
                        <v-toolbar
                            class="custom-toolbar text-white"
                            :color="search_response_personalized.selected_term ? '#2ecc71' : '#9ca3af'"
                            :title="`B_Terme (${search_response_personalized.query})`"
                        >

                        </v-toolbar>
                        <v-card-text id="mathJaxId">
                            <math-jax class="font-weight-bold" style="font-size: 20px" latex="Sim(B\_Terme, Q) = \sum_{ti \in Q} \sum_{tj \in B\_Terme} sim\_terme(t_i,t_j)"></math-jax><br><br>
                            <math-jax class="font-weight-bold" style="font-size: 20px" :latex="`Sim(B\\_Terme, Q) = ${search_response_personalized.sum_terms}`"></math-jax>
                        </v-card-text>
                    </v-card>
                    <v-divider></v-divider>
                    <v-card
                        class="custom-border-selected"
                        :disabled="!search_response_personalized.selected_event"
                    >
                        <v-toolbar
                            class="custom-toolbar text-white"
                            :color="search_response_personalized.selected_event ? '#2ecc71' : '#9ca3af'"
                            :title="`B_Event_p (${search_response_personalized.query})`"
                        >
                        </v-toolbar>
                        <v-card-text id="mathJaxId">
                            <math-jax class="font-weight-bold" style="font-size: 20px" latex="Sim(B\_Event\_p, Q) = \sum_{t \in Q} \sum_{Ep \in B\_event\_p} sim\_event\_p(t_i,Ep_j)"></math-jax><br><br>
                            <math-jax class="font-weight-bold" style="font-size: 20px" :latex="`Sim(B\\_Event\\_p, Q) = ${search_response_personalized.sum_events}`"></math-jax>
                        </v-card-text>
                    </v-card>
                </v-card-text>
                <v-divider class="mt-1 mb-1"></v-divider>
                <v-card-actions>
                    <v-btn
                        class="me-2 text-none text-white"
                        color="#ff993a"
                        variant="flat"
                        block
                        @click="showData()"
                    >
                        Afficher la calculatrice
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Edit profile -->
        <v-dialog
            v-model="dialog_profile"
            width="auto"
            persistent
        >
            <v-form @submit.prevent="updateProfile">
                <v-card :loading="form_profile_loading">
                    <v-toolbar color="#8e8e8e" style="border-bottom: 2px solid #ff993a;">
                        <v-toolbar-title class="text-white font-weight-bold">Modifier le profil</v-toolbar-title>
                        <v-btn class="text-white" icon="mdi-window-close" @click="dialog_profile = false"></v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" v-if="form_profile_error">
                                <v-alert text="Erreur de modification" type="error"></v-alert>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form_profile.full_name"
                                    type="text"
                                    label="Nom et prénom"
                                    hide-details="auto"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form_profile.email"
                                    type="email"
                                    label="Adresse e-mail"
                                    hide-details="auto"
                                    readonly
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form_profile.phone"
                                    type="tel"
                                    label="Numéro de téléphone"
                                    hide-details="auto"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form_profile.age"
                                    type="text"
                                    label="Âge"
                                    hide-details="auto"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-radio-group inline v-model="form_profile.sex" hide-details="auto">
                                    <v-radio label="Femmes" value="women"></v-radio>
                                    <v-radio label="Homme" value="man"></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-divider class="mt-1 mb-1"></v-divider>
                    <v-card-actions>
                        <v-btn
                            type="submit"
                            class="me-2 text-none text-white"
                            color="#ff993a"
                            variant="flat"
                            block
                            :loading="form_profile_loading"
                        >
                            Modifier et enregistrer
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
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
            dialog: false,
            show_data: false,
            dialog_profile: false,
            form_profile:{
                full_name: this.$store.state.userInfo.full_name,
                phone: this.$store.state.userInfo.phone,
                age: this.$store.state.userInfo.age,
                email: this.$store.state.userInfo.email,
                sex: this.$store.state.userInfo.sex,
            },
            form_profile_error: false,
            form_profile_loading: false,
        }
    },
    methods: {
        onSelectMode(mode){
            this.form.mode = mode;
            this.search_response_personalized = null;
            this.search_response_classic = [];
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
            this.show_data = false
            this.dialog = false
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
                        this.dialog = true;
                    }
                }else{
                    this.form.error = true;
                }
            }).catch((error) => {
                this.form.error = true;
            })
            this.form.loading = false
        },
        showData(){
            this.dialog = false;
            this.show_data = true;

        },
        logout(){
            this.$store.commit('setAuthentication', false);
            this.$store.commit('setToken', null);
            this.$store.commit('setUserInfo', null);
            location.reload();
        },
        async updateProfile(){
            this.form_profile_loading = true;
            await axios.post('profile/update', this.form_profile).then(res => {
                const response = res.data;
                if(response.status){
                    this.$store.commit('setUserInfo', response.info);
                    this.dialog_profile = false;
                }else{
                    this.form_profile_error = true;
                }
            }).catch((error) => {
                this.form_profile_error = true;
            })
            this.form_profile_loading = false;
        }
    }
}
</script>

<style scoped>

</style>

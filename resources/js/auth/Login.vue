<template>
    <v-container class="position-relative">
        <v-row justify="center" align="center" class="warp-center">
            <v-col cols="12" md="6" sm="7">
                <v-card class="rounded-3" border>
                    <v-card-text>
                        <v-form @submit.prevent="login">
                            <v-img
                                src="../images/logo.png"
                                width="100"
                                class="m-auto"
                            ></v-img>
                            <v-row class="mt-4">
                                <v-col cols="12" v-if="error">
                                    <v-alert text="Compte invalide" type="error"></v-alert>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        class="mb-2"
                                        v-model="form.email"
                                        type="email"
                                        label="Adresse e-mail"
                                        hide-details="auto"
                                        prepend-inner-icon="mdi-email"
                                        :rules="emailRules"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        label="Mot de passe"
                                        hide-details="auto"
                                        prepend-inner-icon="mdi-lock"
                                        :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        :rules="passwordRules"
                                        @click:append-inner="showPassword = !showPassword"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" class="text-center">
                                    <span>Pas de compte ? <router-link to="/auth/inscription">Créer maintenant</router-link></span>
                                </v-col>
                                <v-col cols="12">
                                    <v-btn
                                        type="submit"
                                        :loading="loading"
                                        class="flex-grow-1 text-white w-100"
                                        height="40"
                                        variant="flat"
                                        color="#3BA0E9"
                                    >Se connecter</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

export default {
    name: "Login",
    data(){
        return {
            loading: false,
            error: false,
            valid: false,
            emailRules: [
                value => {
                    if (value) return true
                    return "L'e-mail est requis."
                },
                value => {
                    if (/.+@.+\..+/.test(value)) return true
                    return "L'email doit être valide."
                },
            ],
            passwordRules: [
                value => {
                    if (value) return true
                    return "Mot de passe requis."
                },
            ],
            form: {
                email: null,
                password: null,
            },
            showPassword: false,
        }
    },
    methods: {
        async login(){
            this.loading = true
            this.error = false
            await axios.post('login', this.form).then(res => {
                const  response = res.data;
                if(response.status){
                    this.$store.commit('setAuthentication', true);
                    this.$store.commit('setToken', response.token);
                    this.$store.commit('setUserInfo', response.info);
                    location.reload();
                }else{
                    this.error = true;
                }
            }).catch((error) => {
                this.error = true;
            })
            this.loading = false
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <v-container class="position-relative">
        <v-row justify="center" align="center" class="warp-center">
            <v-col cols="12" md="6" sm="7">
                <v-card class="rounded-3" border>
                    <v-card-text>
                        <v-form @submit.prevent="inscription">
                            <v-img
                                src="../images/logo.png"
                                width="100"
                                class="m-auto"
                            ></v-img>
                            <v-row class="mt-4">
                                <v-col cols="12" v-if="error">
                                    <v-alert text="Inscription d'erreur. essayer à nouveau" type="error"></v-alert>
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        :items="areas"
                                        v-model="form.area"
                                        label="Domaines"
                                        hide-details="auto"
                                        item-title="name"
                                        item-value="value"
                                        prepend-inner-icon="mdi-lan"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        class="mb-2"
                                        v-model="form.full_name"
                                        type="text"
                                        label="Nom et prénom"
                                        hide-details="auto"
                                        prepend-inner-icon="mdi-account"
                                        required
                                    ></v-text-field>
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
                                        v-model="form.phone"
                                        type="tel"
                                        label="Numéro de téléphone"
                                        hide-details="auto"
                                        prepend-inner-icon="mdi-phone"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.age"
                                        type="text"
                                        label="Âge"
                                        prepend-inner-icon="mdi-calendar-range"
                                        hide-details="auto"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-radio-group inline v-model="form.sex" hide-details="auto">
                                        <v-radio label="Femmes" value="women"></v-radio>
                                        <v-radio label="Homme" value="man"></v-radio>
                                    </v-radio-group>
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
                                    <span>Vous avez déjà un compte ? <router-link to="/auth/login">Se connecter</router-link></span>
                                </v-col>
                                <v-col cols="12">
                                    <v-btn
                                        type="submit"
                                        :loading="loading"
                                        class="flex-grow-1 text-white w-100"
                                        height="40"
                                        variant="flat"
                                        color="#3BA0E9"
                                    >Créer un compte</v-btn>
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
    name: "Inscription",
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
            areas: [//computer_science,policy,medicine,sport,economy
                {name: "L'informatique", value: 'computer_science'},
                {name: 'Politique', value: 'policy'},
                {name: 'Médecine', value: 'medicine'},
                {name: 'Sport', value: 'sport'},
                {name: 'Économie', value: 'economy'},
            ],
            form: {
                full_name: null,
                email: null,
                password: null,
                phone: null,
                age: null,
                area: null,
                sex: 'women',
            },
            showPassword: false,
        }
    },
    methods: {
        async inscription(){
            this.loading = true
            this.error = false
            await axios.post('inscription', this.form).then(res => {
                const  response = res.data;
                if(response.status){
                    this.$router.push('/auth/login');
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

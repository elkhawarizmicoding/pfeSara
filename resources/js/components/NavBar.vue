<template>
    <div class="custom-navbar">
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
                    <div class="position-relative w-100 h-75">
                        <v-icon class="position-absolute pin-search">mdi-magnify</v-icon>
                        <div class="dropdown position-absolute custom-dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Recherche classique
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active">Recherche classique</a></li>
                                <li><a class="dropdown-item">Recherche personnalisé</a></li>
                            </ul>
                        </div>
                        <input class="form-control custom-input h-100" placeholder="Entrez la recherche">
                    </div>
                </div>
                <!-- Account -->
                <v-list class="ml-auto">
                    <v-list-item
                        prepend-avatar="../images/avatar.png"
                        :title="store.state.userInfo.full_name"
                        :subtitle="store.state.userInfo.email"
                    ></v-list-item>
                </v-list>
            </div>
        </v-container>
    </div>
</template>

<script>
import store from "../store/index.js";

export default {
    name: "NavBar",
    computed: {
        store() {
            return store
        }
    }
}
</script>

<style scoped>

</style>

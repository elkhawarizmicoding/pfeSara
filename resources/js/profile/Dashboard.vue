<template>
    <v-layout>
        <NavBar class="position-fixed"></NavBar>
        <div class="w-100 position-relative" style="height: calc(100vh - 5.5em); margin-top: 5.5em">
            <welcome class="position-absolute custom-welcome"></welcome>
        </div>
    </v-layout>
</template>

<script>
import NavBar from "../components/NavBar.vue";
import Welcome from "../components/Welcome.vue";

export default {
    name: "Dashboard",
    components: {NavBar, Welcome}
}
</script>

<style scoped>

</style>

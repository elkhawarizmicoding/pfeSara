<template>
    <div class="d-grid justify-content-center align-items-center">
        <lottie-player
            autoplay
            loop
            mode="bounce"
            src="../lottie/searching.json"
            style="width: 420px; height: 320px"
        ></lottie-player>
        <strong
            class="custom-welcome-text text-center"
            v-text="`Bienvenue ${$store.state.userInfo.full_name.toLowerCase()}`"
        ></strong>
    </div>
</template>

<script>
import * as LottiePlayer from "@lottiefiles/lottie-player";
export default {
    name: "Welcome",

    data() {
        return {
            options: {
                minimizable: false,
                playerSize: "standard",
                backgroundColor: '#fff',
                backgroundStyle: 'color',
                theme: {
                    controlsView: "standard",
                    active: "light",
                    light: {
                        color: '#3D4852',
                        backgroundColor: '#fff',
                        opacity: '0.7',
                    },
                    dark: {
                        color: '#fff',
                        backgroundColor: '#202020',
                        opacity: '0.7',
                    }
                }
            }
        }
    }
}
</script>

<style scoped>

</style>

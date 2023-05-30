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
}
</script>

<style scoped>

</style>

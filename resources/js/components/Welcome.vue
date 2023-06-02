<template>
    <div class="d-grid justify-content-center align-items-center h-100">
        <div class="d-grid" style="height: 400px;gap: 5px">
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

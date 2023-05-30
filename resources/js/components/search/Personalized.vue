<template>
    <v-row>
        <v-col cols="12" md="4" sm="6">
            <v-row>
                <v-col cols="12">
                    <v-card
                        class="mx-auto"
                    >
                        <v-toolbar
                            class="custom-toolbar text-white"
                            color="#8e8e8e"
                            title="Liste des termes"
                        ></v-toolbar>
                        <v-card-text>
                            <v-list lines="one">
                                <v-list-item
                                    v-for="(item, index) in search_response.terms"
                                    :key="index"
                                    :title="item.name"
                                    :subtitle="`Docs (${item.docs}) ~ Poid est ${item.weight}`"
                                ></v-list-item>
                            </v-list>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12">
                    <v-card
                        class="mx-auto"
                    >
                        <v-toolbar
                            class="custom-toolbar text-white"
                            color="#8e8e8e"
                            title="Liste des pass événements"
                        ></v-toolbar>
                        <v-card-text>
                            <v-list lines="one">
                                <v-list-item
                                    v-for="(item, index) in search_response.events"
                                    :key="index"
                                    :title="`${item.name} (${item.lieu})`"
                                    :subtitle="`Docs (${item.docs}) ~ At: ${item.date}`"
                                ></v-list-item>
                            </v-list>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['search_response'],
    name: "Personalized"
}
</script>

<style scoped>

</style>

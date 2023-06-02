<template>
    <h3 class="custom-h3 mb-8 mt-4 font-weight-bold"><v-icon color="#ff993a">mdi-search-web</v-icon> Obtenir des données de Google</h3>
    <v-row>
        <v-col cols="12" md="4" sm="6" v-for="(item, index) in search_response" :key="index">
            <v-badge
                color="#ff993a"
                :content="index+1"
                size="x-large"
            >
                <v-card
                    class="mx-auto custom-card custom-border-left"
                    :title="item.title"
                    :text="item.description"
                >
                    <v-card-actions>
                        <v-btn
                            border
                            class="text-none"
                            prepend-icon="mdi-open-in-new"
                            variant="text"
                            :href="item.url"
                        >
                            Voir plus
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-badge>

        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['search_response'],
    name: "Classic",
    data(){
        return {}
    },
}
</script>

<style scoped>

</style>

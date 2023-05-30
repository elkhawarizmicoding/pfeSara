<template>
    <v-row>
        <v-col cols="12" md="4" sm="6" v-for="(item, index) in search_response" :key="index">
            <v-card
                data-aos="fade-right"
                class="mx-auto custom-border-left"
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

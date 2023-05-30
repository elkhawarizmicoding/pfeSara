<template>
    <v-data-table
        :headers="headers"
        :items="search_response"
        item-value="name"
        class="elevation-1"
    ></v-data-table>
</template>

<script>
export default {
    props: ['search_response'],
    name: "Classic",
    data(){
        return {
            headers: [
                {
                    title: 'Titre',
                    align: 'start',
                    sortable: false,
                    key: 'title',
                },
                { title: 'Description', align: 'end', key: 'description' },
                { title: 'URL', align: 'end', key: 'url' },
            ],
        }
    },
}
</script>

<style scoped>

</style>

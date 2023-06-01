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
                            :title="`Liste des termes (${search_response.terms.length})`"
                        ></v-toolbar>
                        <v-card-text
                            style="height: 450px;"
                            class="overflow-hidden overflow-y-auto"
                        >
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
                            :title="`Liste des pass événements (${search_response.events.length})`"
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
                <v-col cols="12">
                    <v-btn
                        class="me-2 text-none text-white"
                        color="#ff993a"
                        variant="flat"
                        block
                        size="x-large"
                        @click="showReformulate()"
                    >
                        Reformulation de requête
                    </v-btn>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" md="8" sm="6">
            <v-row>
                <v-col cols="12" md="6" v-for="(item, index) in search_response.data" :key="index" :md="item.hasOwnProperty('term') ? 4 : 6">
                    <v-card
                        class="mx-auto custom-border-left"
                        :class="item.hasOwnProperty('term') ? 'border-green' : 'border-grey'"
                        :title="`${item.query} ${item.hasOwnProperty('term') ? item.term : item.event_pass}`"
                    >
                        <v-divider class="mt-0 mb-0"></v-divider>
                        <v-card-text class="d-grid" v-if="item.hasOwnProperty('term')" style="gap: 10px">
                            <div class="d-flex" style="gap: 5px">
                                <span><strong>NB.Docs COM:</strong> ({{item.nb_docs_com}})</span>
                                <span><strong>et NB.Docs T:</strong> ({{item.nb_docs_term}})</span>
                                <span><strong>et NB.Docs Q:</strong> ({{item.nb_docs_query}})</span>
                            </div>

                            <div class="d-grid">
                                <span><strong>Sim(t,t) = </strong>NB.Docs COM / (NB.Docs T + NB.Docs Q)</span>
                                <span><strong>Sim(t,t) = </strong>{{item.nb_docs_com}} / ({{item.nb_docs_term}} + {{item.nb_docs_query}})</span>
                                <span><strong>Sim(t,t) = </strong>{{item.calc}}</span>
                            </div>

                        </v-card-text>
                        <v-card-text class="d-grid" style="gap: 10px" v-else>
                            <div class="d-grid" >
                                <div class="d-flex" style="gap: 5px">
                                    <span><strong>NB.Docs COM:</strong> ({{item.nb_docs_com}})</span>
                                    <span><strong>et NB.Docs E:</strong> ({{item.nb_docs_event}})</span>
                                    <span><strong>et NB.Docs Q:</strong> ({{item.nb_docs_query}})</span>
                                </div>
                                <div class="d-flex" style="gap: 5px">
                                    <span><strong>Poid Q:</strong> ({{item.weight_query}})</span>
                                    <span><strong>Poid E:</strong> ({{item.weight_event}})</span>
                                </div>
                            </div>

                            <div class="d-grid">
                                <span><strong>Sim(t,e) = </strong>(Poid Q * Poid E * NB.Docs COM) / (NB.Docs T + NB.Docs Q)</span>
                                <span><strong>Sim(t,e) = </strong>({{item.weight_query}} * {{item.weight_event}} * {{item.nb_docs_com}}) / ({{item.nb_docs_event}} + {{item.nb_docs_query}})</span>
                                <span><strong>Sim(t,e) = </strong>{{item.calc}}</span>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
    <v-dialog
        v-model="dialogReformulate"
        width="500"
    >
        <v-card>
            <v-toolbar color="#8e8e8e" style="border-bottom: 2px solid #ff993a;">
                <v-toolbar-title class="text-white font-weight-bold">Reformulation de requête</v-toolbar-title>
                <v-btn class="text-white" icon="mdi-window-close" @click="dialogReformulate = false"></v-btn>
            </v-toolbar>
            <v-card-text class="d-grid" style="gap: 5px">
                <span style="font-size: 1.5em; font-weight: bold">Q' = Q + G</span>
                <span style="font-size: 1.5em; font-weight: bold" v-text="req_reformulate_text"></span>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: ['search_response'],
    name: "Personalized",
    data(){
        return {
            dialogReformulate: false,
            req_reformulate_text: '',
        }
    },
    methods: {
        showReformulate(){
            //Q' = {{search_response.query}} + {{search_response.data[0].query}}
            if(this.search_response.selected_term){
                this.req_reformulate_text = `Q' = ${this.search_response.data[0].query} + ${this.search_response.data[0].term}`;
            }else if(this.search_response.selected_event){
                this.req_reformulate_text = `Q' = ${this.search_response.data[0].query} + ${this.search_response.data[0].event_pass}`;
            }
            this.dialogReformulate = true;
        }
    },
    computed: {}
}
</script>

<style scoped>

</style>

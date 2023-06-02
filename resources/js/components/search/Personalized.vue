<template>
    <v-row>
        <v-col cols="12" md="6" v-for="(item, index) in search_response.data.first" :key="index" :md="item.hasOwnProperty('term') ? 4 : 6">
            <v-card
                class="mx-auto custom-border-left border-green"
            >
                <v-card-title>
                    <v-badge
                        color="#ff993a"
                        :content="index+1"
                        size="x-large"
                        inline
                        class="w-100 custom-v-badge"
                    >
                        <span class="d-inline-block w-100" v-text="`${item.query} ${item.hasOwnProperty('term') ? item.term : item.event_pass}`"></span>
                    </v-badge>
                </v-card-title>
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
                        <span><strong>Sim(t,e) = </strong>(Poid Q * Poid E * NB.Docs COM) / (NB.Docs E + NB.Docs Q)</span>
                        <span><strong>Sim(t,e) = </strong>({{item.weight_query}} * {{item.weight_event}} * {{item.nb_docs_com}}) / ({{item.nb_docs_event}} + {{item.nb_docs_query}})</span>
                        <span><strong>Sim(t,e) = </strong>{{item.calc}}</span>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" md="6" v-for="(item, index) in search_response.data.last" :key="index" :md="item.hasOwnProperty('term') ? 4 : 6">
            <v-card
                class="mx-auto custom-border-left border-grey"
            >
                <v-card-title>
                    <v-badge
                        color="#ff993a"
                        :content="lastIndex+(index+1)"
                        size="x-large"
                        inline
                        class="w-100 custom-v-badge"
                    >
                        <span class="d-inline-block w-100" v-text="`${item.query} ${item.hasOwnProperty('term') ? item.term : item.event_pass}`"></span>
                    </v-badge>
                </v-card-title>
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
                        <span><strong>Sim(t,e) = </strong>(Poid Q * Poid E * NB.Docs COM) / (NB.Docs E + NB.Docs Q)</span>
                        <span><strong>Sim(t,e) = </strong>({{item.weight_query}} * {{item.weight_event}} * {{item.nb_docs_com}}) / ({{item.nb_docs_event}} + {{item.nb_docs_query}})</span>
                        <span><strong>Sim(t,e) = </strong>{{item.calc}}</span>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['search_response'],
    name: "Personalized",
    data(){
        return {
            lastIndex: this.search_response.data.first.length,
        }
    },
}
</script>

<style scoped>

</style>

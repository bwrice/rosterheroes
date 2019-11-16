<template>
    <v-container>
        <v-row justify="center" align="center">
            <span class="display-1 rh-op-85 font-weight-bold mx-3 text-center">{{quest.name}}</span>
        </v-row>
        <v-row>
            <v-col v-for="(skirmish, uuid) in quest.skirmishes" :key="uuid" cols="12" sm="6" lg="4" xl="3">
                <SkirmishCard :skirmish="skirmish"></SkirmishCard>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import SkirmishCard from "../../campaign/SkirmishCard";

    export default {
        name: "QuestView",
        components: {SkirmishCard},
        computed: {
            ...mapGetters([
                '_currentLocationQuestBySlug'
            ]),
            quest() {
                let slug = this.$route.params.questSlug;
                return this._currentLocationQuestBySlug(slug);
            }
        }
    }
</script>

<style scoped>

</style>

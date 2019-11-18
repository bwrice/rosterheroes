<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-row no-gutters justify="center" align="center">
                    <span class="display-1 rh-op-85 font-weight-bold mx-3 text-center">{{quest.name}}</span>
                </v-row>
                <v-row no-gutters justify="center" align="center">

                    <v-btn v-if="enlisted"
                           block
                           color="error"
                        >
                        Leave Quest
                    </v-btn>
                    <v-btn v-else
                           color="primary"
                           block
                           :disabled="! canEnlist"
                        >
                        Enlist for Quest
                    </v-btn>
                </v-row>
            </v-col>
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
                '_currentLocationQuestBySlug',
                '_currentCampaign',
                '_enlistedForQuest',
                '_squad'
            ]),
            quest() {
                let slug = this.$route.params.questSlug;
                return this._currentLocationQuestBySlug(slug);
            },
            enlisted() {
                return this._enlistedForQuest(this.quest)
            },
            canEnlist() {
                return this._currentCampaign.campaignStops.length < this._squad.questsPerWeek;
            }
        }
    }
</script>

<style scoped>

</style>

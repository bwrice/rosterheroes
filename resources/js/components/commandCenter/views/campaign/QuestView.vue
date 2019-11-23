<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-row no-gutters justify="center" align="center">
                    <span class="display-1 rh-op-85 font-weight-bold mx-3 text-center">{{quest.name}}</span>
                </v-row>
                <v-row no-gutters align="center" class="mt-2">
                    <v-col cols="12" md="6" offset-md="3" lg="4" offset-lg="4">
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
                               @click="enlist"
                        >
                            Enlist for Quest
                        </v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-row>
            <v-col v-for="(skirmish, uuid) in quest.skirmishes" :key="uuid" cols="12" sm="6" lg="4" xl="3">
                <SkirmishCard :skirmish="skirmish" :quest="quest"></SkirmishCard>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import SkirmishCard from "../../campaign/SkirmishCard";

    export default {
        name: "QuestView",
        components: {SkirmishCard},
        methods: {
            ...mapActions([
                'enlistForQuest'
            ]),
            enlist() {
                this.enlistForQuest({
                    quest: this.quest
                });
            }
        },
        computed: {
            ...mapGetters([
                '_currentLocationQuestBySlug',
                '_currentCampaign',
                '_matchingCampaignStop',
                '_squad'
            ]),
            quest() {
                let slug = this.$route.params.questSlug;
                return this._currentLocationQuestBySlug(slug);
            },
            enlisted() {
                let match = this._matchingCampaignStop(this.quest.uuid);
                return match;
            },
            canEnlist() {
                if (! this._currentCampaign) {
                    return true;
                }
                if (this.enlisted) {
                    return false;
                }
                return this._currentCampaign.campaignStops.length < this._squad.questsPerWeek;
            }
        }
    }
</script>

<style scoped>

</style>

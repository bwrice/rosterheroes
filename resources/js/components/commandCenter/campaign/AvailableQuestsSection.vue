<template>
    <CardSection :title="title" class="mb-2">
        <template v-if="_currentLocationQuests.length">
            <QuestSummaryPanel v-for="(quest, uuid) in _currentLocationQuests" :key="uuid" :quest="quest"></QuestSummaryPanel>
        </template>
        <template v-else>
            <v-sheet color="rgba(255,255,255, 0.25)">
                <v-row no-gutters class="pa-2" justify="center" align="center">
                    <span class="rh-op-70 subtitle-1">{{noQuestsMessage}}</span>
                </v-row>
            </v-sheet>
            <v-row v-if="showTravelButton" no-gutters>
                <v-col cols="8" offset="2">
                    <v-btn :to="travelRoute" block color="primary" class="my-2">Travel to find Quests</v-btn>
                </v-col>
            </v-row>
        </template>
    </CardSection>
</template>

<script>

    import {mapGetters} from 'vuex';
    import QuestSummaryPanel from "../realm/QuestSummaryPanel";
    import CardSection from "../global/CardSection";

    export default {
        name: "AvailableQuestsSection",
        components: {CardSection, QuestSummaryPanel},
        props: {
            showTravelButton: {
                type: Boolean,
                default: true
            },
            titleOverride: {
                type: String,
                default: ""
            }
        },
        computed: {
            ...mapGetters([
                '_currentLocationProvince',
                '_currentLocationQuests'
            ]),
            title() {
                if (this.titleOverride) {
                    return this.titleOverride;
                }
                return 'QUESTS in ' + this._currentLocationProvince.name.toUpperCase()
            },
            noQuestsMessage() {
                return 'No quests in ' + this._currentLocationProvince.name;
            },
            travelRoute() {
                return {
                    name: 'travel',
                    params: {
                        squadSlug: this.$route.params.squadSlug
                    }
                }
            },
        }
    }
</script>

<style scoped>

</style>

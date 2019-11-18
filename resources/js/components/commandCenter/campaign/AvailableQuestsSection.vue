<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{title}}</span>
        </v-col>
        <v-col cols="12" v-if="_currentLocationQuests.length">
            <QuestSummaryPanel v-for="(quest, uuid) in _currentLocationQuests" :key="uuid" :quest="quest"></QuestSummaryPanel>
        </v-col>
        <v-col cols="12" v-else>
            <v-sheet color="rgba(255,255,255, 0.25)">
                <v-row no-gutters class="pa-2" justify="center" align="center">
                    <span class="rh-op-70 subtitle-1">{{noQuestsMessage}}</span>
                </v-row>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>

    import {mapGetters} from 'vuex';
    import QuestSummaryPanel from "../realm/QuestSummaryPanel";

    export default {
        name: "AvailableQuestsSection",
        components: {QuestSummaryPanel},
        computed: {
            ...mapGetters([
                '_currentLocationProvince',
                '_currentLocationQuests'
            ]),
            title() {
                return this._currentLocationProvince.name.toUpperCase() + ' QUESTS'
            },
            noQuestsMessage() {
                return 'No quests in ' + this._currentLocationProvince.name;
            }
        }
    }
</script>

<style scoped>

</style>

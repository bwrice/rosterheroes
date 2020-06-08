<template>
    <v-sheet
        class="pb-2"
        color="#466661"
    >
        <v-row no-gutters justify="center" class="py-2">
            <span class="title rh-op-85">{{sideQuestResult.sideQuest.name}}</span>
        </v-row>
        <v-responsive max-height="400" class="overflow-y-auto pa-1">
            <v-sheet color="rgba(0,0,0,.8)">
                <SideQuestEventPanel
                    v-for="(sideQuestEvent, uuid) in sideQuestEvents"
                    :side-quest-event="sideQuestEvent"
                    :minions="sideQuestResult.sideQuest.minions"
                    :key="uuid"
                ></SideQuestEventPanel>
            </v-sheet>
        </v-responsive>
        <v-row no-gutters>
            <v-col cols="6" class="px-1 pt-1">
                <v-sheet color="rgba(0,0,0,.3)" class="px-2 rh-op-85">
                    <v-row no-gutters justify="space-between">
                        <span class="subtitle-1">Experience:</span>
                        <span class="subtitle-1">{{sideQuestResult.experienceRewarded}}</span>
                    </v-row>
                </v-sheet>
            </v-col>
            <v-col cols="6" class="px-1 pt-1">
                <v-sheet color="rgba(0,0,0,.3)" class="px-2 rh-op-85">
                    <v-row no-gutters justify="space-between">
                        <span class="subtitle-1">Favor:</span>
                        <span class="subtitle-1">{{sideQuestResult.favorRewarded}}</span>
                    </v-row>
                </v-sheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import SideQuestResult from "../../../models/SideQuestResult";
    import SideQuestEventPanel from "./SideQuestEventPanel";

    export default {
        name: "SideQuestResultReplay",
        components: {SideQuestEventPanel},
        props: {
            sideQuestResult: {
                type: SideQuestResult,
                required: true
            },
            sideQuestEvents: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

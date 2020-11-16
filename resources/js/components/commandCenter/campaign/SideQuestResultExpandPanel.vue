<template>
    <v-sheet
        class="rounded-sm"
        color="#464d4c"
    >
        <v-row no-gutters align="center" class="py-1">
            <v-col cols="7">
                <v-row no-gutters class="pl-2">
                    <span>{{sideQuestResult.sideQuestSnapshot.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="5">
                <v-row
                    no-gutters
                    justify="end"
                    align="center"
                >
                    <v-btn
                        color="primary"
                        small
                        class="mx-1"
                    >
                        Replay
                    </v-btn>
                    <v-btn @click="expanded = ! expanded"
                           fab
                           dark
                           x-small
                           color="rgba(0, 0, 0, .4)"
                           class="mx-1"
                    >
                        <v-icon v-if="expanded">expand_less</v-icon>
                        <v-icon v-else>expand_more</v-icon>
                    </v-btn>
                </v-row>
            </v-col>
        </v-row>
        <v-row v-if="expanded" no-gutters>
            <v-col cols="12" class="px-1 pb-1">
                <v-card>
                    <v-card-title class="text-center rh-op-85">
                        {{sideQuestResult.sideQuestSnapshot.name}}
                    </v-card-title>
                    <v-divider></v-divider>
                    <TabbedItems :items="sideQuestResult.sideQuestSnapshot.minionSnapshots">
                        <template v-slot:tab="{item}">
                            {{item.name}}
                        </template>
                        <template v-slot:default="{item}">
                            <MinionSnapshotTab :minion-snapshot="item"></MinionSnapshotTab>
                        </template>
                    </TabbedItems>
                </v-card>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import SideQuestResult from "../../../models/SideQuestResult";
    import TabbedItems from "../global/TabbedItems";
    import MinionSnapshotTab from "./MinionSnapshotTab";

    export default {
        name: "SideQuestResultExpandPanel",
        components: {MinionSnapshotTab, TabbedItems},
        props: {
            sideQuestResult: {
                type: SideQuestResult,
                required: true
            }
        },
        data() {
            return {
                expanded: false
            }
        }
    }
</script>

<style scoped>

</style>

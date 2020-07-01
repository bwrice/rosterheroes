<template>
    <v-sheet color="#466661" class="my-1 rounded-sm">
        <v-row no-gutters class="pa-1" justify="center" align="center">
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-chip
                        label
                        color="rgba(0,0,0,.25)"
                        v-on="on"
                    >
                        {{sideQuest.difficulty}}
                    </v-chip>
                </template>
                <span>difficulty ({{sideQuest.difficulty}})</span>
            </v-tooltip>
            <v-col cols="5 subtitle-1 rh-op-90 font-weight-regular mx-1 text-truncate">
                {{sideQuest.name}}
            </v-col>
            <div class="flex flex-grow-1"></div>
            <slot name="action">
                <!-- slot for action buttons such as join side-quest -->
            </slot>
            <v-btn @click="expanded = ! expanded"
                   fab
                   dark
                   x-small
                   color="rgba(0, 0, 0, .4)"
            >
                <v-icon v-if="expanded">expand_less</v-icon>
                <v-icon v-else>expand_more</v-icon>
            </v-btn>
        </v-row>
        <v-row no-gutters v-if="expanded">
            <v-col cols="12" class="pa-1">
                <v-card
                    color="#32343d"
                >
                    <v-card-title>
                        <span class="text-center rh-op-85">
                            {{sideQuest.name}}
                        </span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-tabs
                        v-model="tab"
                        mobile-breakpoint="10"
                        centered
                        background-color="#32343d"
                        color="#b3c9c3"
                        slider-color="primary"
                    >
                        <v-tab
                            v-for="(minion, uuid) in sideQuest.minions"
                            :key="uuid"
                        >
                            {{ minion.name }}
                        </v-tab>
                    </v-tabs>
                    <v-tabs-items v-model="tab" style="background-color: transparent">
                        <SideQuestMinionTab
                            v-for="(minion, uuid) in sideQuest.minions"
                            :key="uuid"
                            :minion="minion"
                        ></SideQuestMinionTab>
                    </v-tabs-items>
                </v-card>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>

    import SideQuest from "../../../models/SideQuest";
    import SideQuestMinionTab from "../views/campaign/SideQuestMinionTab";

    export default {
        name: "SideQuestPanel",
        components: {SideQuestMinionTab},
        props: {
            sideQuest: {
                type: SideQuest,
                required: true
            },
            height: {
                type: Number,
                default: 300
            }
        },
        data() {
            return {
                pending: false,
                expanded: false,
                tab: null
            }
        }
    }
</script>

<style scoped>

</style>

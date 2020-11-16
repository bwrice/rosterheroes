<template>
    <v-tab-item class="pa-1">
        <v-row no-gutters justify="center" align="center" class="mb-1">
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-chip
                        label
                        color="rgba(255,255,255,.25)"
                        v-on="on"
                    >
                        {{minion.level}}
                    </v-chip>
                </template>
                <span>minion level ({{minion.level}})</span>
            </v-tooltip>
            <div class="flex flex-grow-1"></div>
            <span class="title font-weight-light mx-2">
                {{title}}
            </span>
            <div class="flex flex-grow-1"></div>
        </v-row>
        <v-row no-gutters>
            <v-col cols="12">
                <v-row no-gutters align="center">
                    <v-col cols="7" offset="1">
                        <v-sheet
                            color="rgba(255,255,255,.25)"
                            v-for="(stat, name) in stats"
                            :key="name"
                            class="mb-1 px-2 rounded-sm"
                        >
                            <v-row no-gutters justify="space-between">
                                <span class="subtitle-2 rh-op-75">{{stat.title}}</span>
                                <span class="subtitle-2 rh-op-75">{{stat.value}}</span>
                            </v-row>
                        </v-sheet>
                    </v-col>
                    <v-col cols="2" offset="1">
                        <CombatPositionIcon
                            :combat-position-id="minion.combatPositionID"
                            :with-prefix="false"
                            :attacker-mode="false"
                        ></CombatPositionIcon>
                    </v-col>
                </v-row>
                <slot>
                    <!-- Default Slot -->
                </slot>
            </v-col>
        </v-row>
    </v-tab-item>
</template>

<script>
    import BaseMinion from "../../../models/BaseMinion";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";

    export default {
        name: "BaseMinionTab",
        components: {CombatPositionIcon},
        props: {
            minion: {
                type: BaseMinion,
                required: true
            }
        },
        computed: {
            title() {
                return this.minion.name + ' (x' + this.minion.count + ')';
            },
            stats() {
                return [
                    {
                        title: 'HEALTH',
                        value: this.minion.startingHealth
                    },
                    {
                        title: 'PROTECTION',
                        value: this.minion.protection
                    },
                    {
                        title: 'BLOCK',
                        value: this.minion.blockChance + '%'
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-card color="blue-grey darken-1">
        <v-card-title class="primary-title">
            <div>
                <h4 class="headline mb-0">{{ playerName }}</h4>
                <v-layout class="row wrap">
                    <v-flex class="xs8">
                        <v-layout class="row wrap">
                            <v-flex class="xs12">
                                {{ gameDescription }}
                            </v-flex>
                            <v-flex class="xs12">
                                Essence Cost: {{ essenceCost }}
                            </v-flex>
                            <v-flex class="xs12">
                                Energy: {{ energy }}
                            </v-flex>
                            <v-flex class="xs12">
                                <PositionChipList :positions="playerSpiritPositions"></PositionChipList>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex class="xs4">
                        <slot name="spirit-actions">
                            <!-- Slotted Spirit Actions -->
                        </slot>
                    </v-flex>
                </v-layout>
            </div>
        </v-card-title>
    </v-card>
</template>

<script>
    import PositionChipList from "./PositionChipList";
    import PlayerSpirit from "../../../models/PlayerSpirit";

    export default {
        name: "PlayerSpiritPanel",
        components: {PositionChipList},
        props: ['playerSpirit'],

        computed: {
            playerSpiritPositions: function() {
                // TODO filter out overlapping positions when new Hero Races are added
                return this.playerSpirit.player.positions;
            },
            playerName: function() {
                let playerSpirit = new PlayerSpirit(this.playerSpirit);
                return playerSpirit.playerName;
            },
            gameDescription: function() {
                let playerSpirit = new PlayerSpirit(this.playerSpirit);
                return playerSpirit.gameDescription;
            },
            essenceCost: function() {
                return this.playerSpirit.essence_cost;
            },
            energy: function() {
                return this.playerSpirit.energy;
            }
        }
    }
</script>

<style scoped>

</style>
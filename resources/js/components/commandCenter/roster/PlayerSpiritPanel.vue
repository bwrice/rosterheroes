<template>
    <v-sheet color="#29272b" class="px-1 rounded" style="margin-bottom: 1px; color: rgba(255, 255, 255, 0.9)">
        <v-row no-gutters justify="space-between" align="center">
            <span class="title">{{ playerSpirit.fullName }}</span>
            <PositionChipList :positions="positions"></PositionChipList>
        </v-row>
        <v-row no-gutters>
            <v-col cols="4">
                <v-row no-gutters class="flex-column">
                    <span class="caption font-weight-light">{{ gameDescription }}</span>
                    <SpiritEnergyBar :energy="playerSpirit.energy"></SpiritEnergyBar>
                </v-row>
            </v-col>
            <v-col cols="8">
                <v-row no-gutters align="center">
                    <v-col cols="5">
                        <v-row no-gutters justify="end">
                            <span class="headline font-weight-bold">{{ playerSpirit.essenceCost.toLocaleString() }}</span>
                        </v-row>
                    </v-col>
                    <v-col cols="7">
                        <v-row no-gutters justify="end">
                            <slot name="spirit-actions">
                                <!-- Slotted Spirit Actions -->
                            </slot>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import PositionChipList from "./PositionChipList";
    import PlayerSpirit from "../../../models/PlayerSpirit";

    import {mapGetters} from 'vuex';
    import SpiritEnergyBar from "../global/SpiritEnergyBar";

    export default {
        name: "PlayerSpiritPanel",
        components: {SpiritEnergyBar, PositionChipList},
        props: {
            playerSpirit: {
                type: PlayerSpirit,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_gameDescriptionByGameID',
                '_positionsFilteredByIDs'
            ]),
            gameDescription() {
                return this._gameDescriptionByGameID(this.playerSpirit.playerGameLog.gameID);
            },
            positions() {
                return this._positionsFilteredByIDs(this.playerSpirit.playerGameLog.player.positionIDs);
            }
        }
    }
</script>

<style scoped>

</style>

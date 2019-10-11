<template>
    <transition name="fade">
        <v-sheet color="#29272b" class="mx-1 px-1" style="margin-bottom: 1px; color: rgba(255, 255, 255, 0.9)">
            <v-row no-gutters justify="space-between" align="center">
                <span class="title">{{ playerSpirit.fullName }}</span>
                <PositionChipList :positions="positions"></PositionChipList>
            </v-row>
            <v-row no-gutters>
                <v-col cols="4">
                    <v-row no-gutters class="flex-column">
                        <span class="caption font-weight-light">{{ gameDescription }}</span>
                        <span class="caption font-weight-light">{{ gameDescription }}</span>
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
    </transition>
</template>

<script>
    import PositionChipList from "./PositionChipList";
    import PlayerSpirit from "../../../models/PlayerSpirit";

    import {mapGetters} from 'vuex';

    export default {
        name: "PlayerSpiritPanel",
        components: {PositionChipList},
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
                return this._gameDescriptionByGameID(this.playerSpirit.gameID);
            },
            positions() {
                return this._positionsFilteredByIDs(this.playerSpirit.player.positionIDs);
            }
        }
    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
</style>

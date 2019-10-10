<template>
    <transition name="fade">
        <v-card>
            <v-card-title class="primary-title">
                {{ playerSpirit.fullName }}
            </v-card-title>
            <v-card-text>
                <v-row no-gutters>
                    <v-col cols="8">
                        <p class="ma-0">{{ gameDescription }}</p>
                        <p class="ma-0">Essence Cost: {{ playerSpirit.essenceCost }}</p>
                        <p class="ma-0">Energy: {{ playerSpirit.energy }}</p>
                    </v-col>
                    <v-col cols="4">
                        <slot name="spirit-actions">
                            <!-- Slotted Spirit Actions -->
                        </slot>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12">
                        <PositionChipList :positions="positions"></PositionChipList>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </transition>
</template>

<script>
    import PositionChipList from "./PositionChipList";
    // import { playerSpiritMixin } from '../../../mixins/playerSpiritMixin';
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
                '_gameByID',
                '_teamByID',
                '_positionsFilteredByIDs'
            ]),
            gameDescription() {
                let game = this._gameByID(this.playerSpirit.gameID);
                let homeTeam = this._teamByID(game.homeTeamID);
                let awayTeam = this._teamByID(game.awayTeamID);
                return awayTeam.abbreviation + '@' + homeTeam.abbreviation;
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

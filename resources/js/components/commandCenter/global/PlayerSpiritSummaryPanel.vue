<template>
    <v-sheet
        color="#29272b"
        :elevation="elevation"
        class="ma-1 px-2"
        @click="emitClick"
    >
        <v-row no-gutters>
            <v-col cols="12">
                <span class="subtitle-1 font-weight-regular">{{playerSpirit.player.fullName}}</span>
            </v-col>
        </v-row>
        <v-row no-gutters justify="space-between">
            <span class="caption font-weight-regular">{{gameDescription}}</span>
            <span class="title font-weight-bold">{{playerSpirit.essenceCost.toLocaleString()}}</span>
        </v-row>
    </v-sheet>
</template>

<script>
    import PlayerSpirit from "../../../models/PlayerSpirit";
    import {mapGetters} from 'vuex';

    export default {
        name: "PlayerSpiritSummaryPanel",
        props: {
            playerSpirit: {
                type: PlayerSpirit,
                required: true
            },
            clickable: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            emitClick() {
                if (this.clickable) {
                    this.$emit('playerSpiritClicked');
                }
            }
        },
        computed: {
            ...mapGetters([
                '_gameDescriptionByGameID'
            ]),
            elevation() {
                return this.clickable ? 4 : 0;
            },
            gameDescription() {
                return this._gameDescriptionByGameID(this.playerSpirit.gameID);
            }
        }
    }
</script>

<style scoped>

</style>

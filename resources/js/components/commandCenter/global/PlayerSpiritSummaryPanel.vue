<template>
    <v-sheet
        :color="sheetColor"
        :elevation="elevation"
        class="ma-1 px-2"
        @click="emitClick"
    >
        <template v-if="playerSpirit">
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="subtitle-1 font-weight-regular">{{playerSpirit.player.fullName}}</span>
                </v-col>
            </v-row>
            <v-row no-gutters justify="space-between">
                <span class="caption font-weight-regular">{{playerSpirit.game.simpleDescription}}</span>
                <span class="title font-weight-bold">{{playerSpirit.essenceCost.toLocaleString()}}</span>
            </v-row>
        </template>
        <template v-else>
            <v-row justify="center" align="center">
                <span class="subtitle-2 my-4">{{ emptySpiritText }}</span>
            </v-row>
        </template>
    </v-sheet>
</template>

<script>
    import PlayerSpirit from "../../../models/PlayerSpirit";

    export default {
        name: "PlayerSpiritSummaryPanel",
        props: {
            playerSpirit: {
                type: PlayerSpirit
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
            sheetColor() {
                if (this.playerSpirit) {
                    if (this.clickable) {
                        return '#32a4a8'
                    }
                    return '#228487';
                } else {
                    if (this.clickable) {
                        return 'success';
                    }
                }
                return 'rgba(0, 0, 0, .2)';
            },
            elevation() {
                return this.clickable ? 2 : 0;
            },
            emptySpiritText() {
                if (this.clickable) {
                    return 'add spirit';
                }
                return '(no spirit)';
            }
        }
    }
</script>

<style scoped>

</style>

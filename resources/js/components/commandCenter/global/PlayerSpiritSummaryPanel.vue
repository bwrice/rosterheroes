<template>
    <v-sheet :color="sheetColor" class="ma-1 px-2" style="color: rgba(255, 255, 255, .9)">
        <template v-if="playerSpirit">
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="subtitle-1 font-weight-regular">{{playerSpirit.player.fullName}}</span>
                </v-col>
            </v-row>
            <v-row no-gutters justify="center">
                <span class="caption font-weight-light">{{playerSpirit.game.simpleDescription}}</span>
                <div class="flex-grow-1"></div>
                <span class="title font-weight-bold">{{playerSpirit.essenceCost.toLocaleString()}}</span>
            </v-row>
        </template>
        <template v-else>
            <v-row justify="center" align="center">
                <span class="subtitle-2 my-4">(no spirit)</span>
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
            }
        },
        computed: {
            sheetColor() {
                if (this.playerSpirit) {
                    return 'rgba(43, 140, 122, .85)';
                }
                return 'rgba(0, 0, 0, .2)';
            }
        }
    }
</script>

<style scoped>

</style>

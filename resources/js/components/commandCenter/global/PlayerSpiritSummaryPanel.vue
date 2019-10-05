<template>
    <v-sheet color="rgba(255, 255, 255, .5)" class="ma-1 px-2" style="color: #322638">
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
    </v-sheet>
</template>

<script>
    import PlayerSpirit from "../../../models/PlayerSpirit";

    export default {
        name: "PlayerSpiritSummaryPanel",
        props: {
            playerSpirit: {
                type: PlayerSpirit,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

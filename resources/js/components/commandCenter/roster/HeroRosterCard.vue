<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
        </v-card-title>
        <div v-if="hero.playerSpirit">
            <PlayerSpiritPanel :player-spirit="hero.playerSpiritObject">
                <template v-slot:spirit-actions>
                    <EditSpiritButton :hero="hero"></EditSpiritButton>
                    <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpiritObject"></RemoveSpiritButton>
                </template>
            </PlayerSpiritPanel>
        </div>
        <div v-else>
            <EditSpiritButton :hero="hero"></EditSpiritButton>
        </div>
    </v-card>
</template>

<script>

    import PlayerSpiritPanel from "./PlayerSpiritPanel";
    import RemoveSpiritButton from "./RemoveSpiritButton";
    import EditSpiritButton from "./EditSpiritButton";

    export default {
        name: "HeroRosterCard",
        components: {EditSpiritButton, RemoveSpiritButton, PlayerSpiritPanel},
        props: ['hero']
    }
</script>

<style scoped>

</style>
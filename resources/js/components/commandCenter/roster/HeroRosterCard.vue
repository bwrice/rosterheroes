<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
        </v-card-title>
        <div v-if="hero.playerSpirit">
            <PlayerSpiritPanel :player-spirit="getPlayerSpirit(hero.playerSpirit)">
                <template v-slot:spirit-actions>
                    <EditSpiritButton :hero="hero"></EditSpiritButton>
                    <RemoveSpiritButton :hero="hero" :player-spirit="getPlayerSpirit(hero.playerSpirit)"></RemoveSpiritButton>
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
    import PlayerSpirit from "../../../models/PlayerSpirit";

    export default {
        name: "HeroRosterCard",
        props: ['hero'],
        components: {
            EditSpiritButton,
            RemoveSpiritButton,
            PlayerSpiritPanel
        },
        methods: {
            getPlayerSpirit: function(playerSpirit) {
                return new PlayerSpirit(playerSpirit)
            }
        }
    }
</script>

<style scoped>

</style>
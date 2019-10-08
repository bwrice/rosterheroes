<template>
    <v-card>
        <v-card-text>
            <v-row no-gutters align="center">
                <v-col cols="4">
                    <v-row no-gutters>
                        <v-col cols="6" class="py-0">
                            <HeroRaceIcon :hero-race-id="hero.heroRaceID"></HeroRaceIcon>
                        </v-col>
                        <v-col cols="6">
                            <HeroClassIcon :hero-class-id="hero.heroClassID"></HeroClassIcon>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="6">
                    <PlayerSpiritSummaryPanel :player-spirit="hero.playerSpirit"></PlayerSpiritSummaryPanel>
                </v-col>
                <v-col cols="2">
                    <CombatPositionIcon :combat-position-id="hero.combatPositionID" :attacker-mode="true"></CombatPositionIcon>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    import BarracksHero from "../../../models/BarracksHero";
    import HeroRaceIcon from "../global/HeroRaceIcon";
    import HeroClassIcon from "../global/HeroClassIcon";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import CombatPositionIcon from "../global/CombatPositionIcon";

    export default {
        name: "HeroHeader",
        components: {CombatPositionIcon, PlayerSpiritSummaryPanel, HeroClassIcon, HeroRaceIcon},
        props: {
            hero: {
                type: BarracksHero,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

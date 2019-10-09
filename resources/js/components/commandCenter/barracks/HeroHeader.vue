<template>
    <v-card>
        <v-row no-gutters align="center" class="py-2">
            <v-col cols="9" class="pl-2">
                <PlayerSpiritSummaryPanel
                    :player-spirit="hero.playerSpirit"
                    :clickable="true"
                    @playerSpiritClicked="goToEditRoster"
                >
                </PlayerSpiritSummaryPanel>
            </v-col>
            <v-col cols="3" class="px-2">
                <CombatPositionIcon :combat-position-id="hero.combatPositionID" :attacker-mode="true"></CombatPositionIcon>
            </v-col>
        </v-row>
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
        components: {
            CombatPositionIcon,
            PlayerSpiritSummaryPanel,
            HeroClassIcon,
            HeroRaceIcon
        },
        props: {
            hero: {
                type: BarracksHero,
                required: true
            }
        },
        methods: {
            goToEditRoster() {
                this.$router.push({
                    name: 'roster-hero',
                    squadSlug: this.$route.params.squadSlug,
                    heroSlug: this.hero.slug
                })
            }
        }
    }
</script>

<style scoped>

</style>

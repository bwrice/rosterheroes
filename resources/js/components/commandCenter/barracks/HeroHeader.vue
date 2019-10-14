<template>
    <v-card>
        <v-row no-gutters align="center" class="py-2">
            <v-col cols="9" class="pl-2">
                <PlayerSpiritSummaryPanel
                    v-if="hero.playerSpirit"
                    :player-spirit="hero.playerSpirit"
                    :clickable="true"
                    @playerSpiritClicked="goToEditRoster"
                >
                </PlayerSpiritSummaryPanel>
                <AddSpiritRouterButton
                    v-else
                    :hero-slug="hero.slug"
                >
                </AddSpiritRouterButton>
            </v-col>
            <v-col cols="3" class="px-2">
                <CombatPositionIcon
                    :combat-position-id="hero.combatPositionID"
                    :attacker-mode="true"
                    :clickable="true"
                    @combatPositionClicked="combatPositionDialog = true"
                >
                </CombatPositionIcon>
                <v-dialog v-model="combatPositionDialog">
                </v-dialog>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
    import Hero from "../../../models/Hero";
    import HeroRaceIcon from "../global/HeroRaceIcon";
    import HeroClassIcon from "../global/HeroClassIcon";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import CombatPositionIcon from "../global/CombatPositionIcon";
    import AddSpiritRouterButton from "../global/AddSpiritRouterButton";

    import {mapGetters} from 'vuex';

    export default {
        name: "HeroHeader",
        components: {
            AddSpiritRouterButton,
            CombatPositionIcon,
            PlayerSpiritSummaryPanel,
            HeroClassIcon,
            HeroRaceIcon
        },
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        data() {
            return {
                combatPositionDialog: false
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

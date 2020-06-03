<template>
    <v-row no-gutters align="center" class="py-2">
        <v-col cols="9" class="pl-2">
            <div v-if="hero.playerSpirit" class="rh-clickable">
                <PlayerSpiritSummaryPanel
                    :player-spirit="hero.playerSpirit"
                    :clickable="true"
                    @playerSpiritClicked="goToEditRoster"
                >
                </PlayerSpiritSummaryPanel>
            </div>
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
                :elevation="4"
                @click.native="combatPositionDialog = true"
                class="rh-clickable"
                :tool-tip="false"
            >
            </CombatPositionIcon>
            <v-dialog v-model="combatPositionDialog" max-width="600">
                <CombatPositionDialog
                    @close="combatPositionDialog = false"
                    :hero="hero"
                >
                </CombatPositionDialog>
            </v-dialog>
        </v-col>
    </v-row>
</template>

<script>
    import Hero from "../../../models/Hero";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";
    import AddSpiritRouterButton from "../global/AddSpiritRouterButton";
    import CombatPositionDialog from "./CombatPositionDialog";

    export default {
        name: "HeroHeader",
        components: {
            CombatPositionDialog,
            AddSpiritRouterButton,
            CombatPositionIcon,
            PlayerSpiritSummaryPanel
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

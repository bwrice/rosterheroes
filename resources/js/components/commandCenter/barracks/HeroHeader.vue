<template>
    <v-sheet color="#5c707d">
        <v-row no-gutters align="center" class="py-2">
            <v-col cols="9" class="pl-2">
                <v-row no-gutters class="mb-2">
                    <v-col cols="12">
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
                </v-row>
                <StatRow
                    v-for="(stat, name) in stats"
                    :key="name"
                    :name="stat.name"
                    :value="stat.value"
                ></StatRow>
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
    </v-sheet>
</template>

<script>
    import Hero from "../../../models/Hero";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";
    import AddSpiritRouterButton from "../global/AddSpiritRouterButton";
    import CombatPositionDialog from "./CombatPositionDialog";
    import StatRow from "../global/StatRow";

    export default {
        name: "HeroHeader",
        components: {
            StatRow,
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
        },
        computed: {
            stats() {
                return [
                    {
                        name: 'Damage Per Moment',
                        value: this.hero.damagePerMoment
                    },
                    {
                        name: 'Protection',
                        value: this.hero.protection
                    },
                    {
                        name: 'Block Chance %',
                        value: this.hero.blockChance
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>

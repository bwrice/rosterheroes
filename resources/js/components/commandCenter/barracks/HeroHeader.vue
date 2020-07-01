<template>
    <v-sheet color="#445866" class="rounded">
        <v-row no-gutters align="center" class="py-2">
            <v-col cols="8" md="9" class="pl-2">
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
            <v-col cols="4" md="3" class="px-2">
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
            <v-col cols="12" class="pt-2">
                <v-row no-gutters class="px-2 py-1" align="center">
                    <v-col cols="2">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-sheet v-on="on" class="mx-1 rounded-sm" color="rgba(255,255,255, 0.25)">
                                    <v-row no-gutters justify="center">
                                        <span class="text-body-2" style="cursor: default">MwS</span>
                                    </v-row>
                                </v-sheet>
                            </template>
                            <span>Moments with Stamina</span>
                        </v-tooltip>
                    </v-col>
                    <v-col cols="10" class="pr-1">
                        <v-progress-linear
                            :value="momentsWithStaminaProgress"
                            color="#cfc07e"
                            height="20"
                        >
                            <strong>{{ momentsWithStamina }}</strong>
                        </v-progress-linear>
                    </v-col>
                </v-row>
                <v-row no-gutters class="px-2 py-1" align="center">
                    <v-col cols="2">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-sheet v-on="on" class="mx-1 rounded-sm" color="rgba(255,255,255, 0.25)">
                                    <v-row no-gutters justify="center">
                                        <span class="text-body-2" style="cursor: default">MwM</span>
                                    </v-row>
                                </v-sheet>
                            </template>
                            <span>Moments with Mana</span>
                        </v-tooltip>
                    </v-col>
                    <v-col cols="10" class="pr-1">
                        <v-progress-linear
                            :value="momentsWithManaProgress"
                            color="#7e95cf"
                            height="20"
                        >
                            <strong>{{ momentsWithMana }}</strong>
                        </v-progress-linear>
                    </v-col>
                </v-row>
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
            },
            momentsWithStaminaProgress() {
                if (this.hero.momentsWithStamina === 'infinite') {
                    return 100;
                }
                return 5 * Math.sqrt(this.hero.momentsWithStamina);
            },
            momentsWithStamina() {
                if (this.hero.momentsWithStamina === 'infinite') {
                    return '∞';
                }
                return this.hero.momentsWithStamina;
            },
            momentsWithManaProgress() {
                if (this.hero.momentsWithMana === 'infinite') {
                    return 100;
                }
                return 5 * Math.sqrt(this.hero.momentsWithMana);
            },
            momentsWithMana() {
                if (this.hero.momentsWithMana === 'infinite') {
                    return '∞';
                }
                return this.hero.momentsWithMana;
            }
        }
    }
</script>

<style scoped>

</style>

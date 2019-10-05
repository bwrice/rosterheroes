<template>
    <v-sheet
        color="rgba(255, 255, 255, 0.15)"
        class="my-1"
        elevation="5"
        @click="navigateToBarracksHero"
    >
        <v-row no-gutters align="center">
            <v-col cols="4">
                <v-row no-gutters>
                    <v-col cols="6" class="py-0">
                        <SvgIconSheet
                            :color="'#C8DDE0'"
                            :svg="hero.heroRace.svg"
                            :classes-object="{'pa-1': true}"
                        >
                        </SvgIconSheet>
                    </v-col>
                    <v-col cols="6">
                        <SvgIconSheet
                            :color="'#C8DDE0'"
                            :svg="hero.heroClass.svg"
                            :classes-object="{'pa-1': true}"
                        >
                        </SvgIconSheet>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="6">
                <v-row align="center" justify="center">
                    <span class="title font-weight-regular">{{hero.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="2">
                <SvgIconSheet :svg="hero.combatPosition.svg">
                </SvgIconSheet>
            </v-col>
        </v-row>
        <v-row no-gutters align="center">
            <v-col cols="4">
                <v-sheet class="py-0 ma-1" style="background-image: linear-gradient(to bottom right, #393142, #5e526b , #393142)">
                    <HeroGearSVG :hero="hero"></HeroGearSVG>
                </v-sheet>
            </v-col>
            <v-col cols="8">
                <v-row no-gutters>
                    <v-col cols="12">
                        <PlayerSpiritSummaryPanel :player-spirit="hero.playerSpirit"></PlayerSpiritSummaryPanel>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12" class="px-1 pb-1">
                        <RelativeMeasurableBar :color="'#c26161'" :measurable="heroHealth"></RelativeMeasurableBar>
                        <RelativeMeasurableBar :color="'#ded337'" :measurable="heroStamina"></RelativeMeasurableBar>
                        <RelativeMeasurableBar :color="'#43a1e8'" :measurable="heroMana"></RelativeMeasurableBar>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import SvgIconSheet from "../global/SvgIconSheet";
    import HeroGearSVG from "./gear/HeroGearSVG";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import BarracksHero from "../../../models/BarracksHero";

    import {mapGetters} from 'vuex';
    import RelativeMeasurableBar from "./RelativeMeasurableBar";

    export default {
        name: "HeroSummaryPanel",
        components: {RelativeMeasurableBar, PlayerSpiritSummaryPanel, HeroGearSVG, SvgIconSheet},
        props: {
            hero: {
                type: BarracksHero,
                require: true
            }
        },
        methods: {
            navigateToBarracksHero() {
                this.$router.push(this.barracksHeroRoute);
            }
        },
        computed: {
            ...mapGetters([
                '_squadHighMeasurable'
            ]),
            heroHealth() {
                return this.hero.getMeasurableByType('health')
            },
            heroStamina() {
                return this.hero.getMeasurableByType('stamina')
            },
            heroMana() {
                return this.hero.getMeasurableByType('mana')
            },
            barracksHeroRoute() {
                let squadSlugParam = this.$route.params.squadSlug;
                return {
                    name: 'barracks-hero',
                    params: {
                        squadSlug: squadSlugParam,
                        heroSlug: this.hero.slug
                    }
                }
            },
        }
    }
</script>

<style scoped>

</style>

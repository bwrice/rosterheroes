<template>
    <v-sheet
        color="#576269"
        class="my-2"
        elevation="5"
        @click="navigateToBarracksHero"
    >
        <v-row no-gutters align="center">
            <v-col cols="4">
                <v-row no-gutters>
                    <v-col cols="6">
                        <HeroRaceIcon :hero-race-id="hero.heroRaceID"></HeroRaceIcon>
                    </v-col>
                    <v-col cols="6">
                        <HeroClassIcon :hero-class-id="hero.heroClassID"></HeroClassIcon>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="6">
                <v-row align="center" justify="center">
                    <span class="title font-weight-regular">{{hero.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="2">
                <CombatPositionIcon :combat-position-id="hero.combatPositionID" :attacker-mode="true"></CombatPositionIcon>
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
                        <PlayerSpiritSummaryPanel
                            v-if="hero.playerSpirit"
                            :player-spirit="hero.playerSpirit"
                        ></PlayerSpiritSummaryPanel>
                        <v-sheet
                            v-else
                            color="rgba(0, 0, 0, .2)"
                            class="ma-1 px-2">
                            <v-row justify="center" align="center">
                                <span class="subtitle-2 my-4">no spirit</span>
                            </v-row>
                        </v-sheet>
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
    import Hero from "../../../models/Hero";

    import {mapGetters} from 'vuex';
    import RelativeMeasurableBar from "./RelativeMeasurableBar";
    import HeroClassIcon from "../global/HeroClassIcon";
    import HeroRaceIcon from "../global/HeroRaceIcon";
    import CombatPositionIcon from "../global/CombatPositionIcon";

    export default {
        name: "HeroSummaryPanel",
        components: {
            CombatPositionIcon,
            HeroRaceIcon,
            HeroClassIcon, RelativeMeasurableBar, PlayerSpiritSummaryPanel, HeroGearSVG, SvgIconSheet},
        props: {
            hero: {
                type: Hero,
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
                '_squadHighMeasurable',
                '_measurableTypeByName'
            ]),
            heroHealth() {
                let measurableType = this._measurableTypeByName('health');
                return this.hero.getMeasurableByTypeID(measurableType.id)
            },
            heroStamina() {
                let measurableType = this._measurableTypeByName('stamina');
                return this.hero.getMeasurableByTypeID(measurableType.id)
            },
            heroMana() {
                let measurableType = this._measurableTypeByName('mana');
                return this.hero.getMeasurableByTypeID(measurableType.id)
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

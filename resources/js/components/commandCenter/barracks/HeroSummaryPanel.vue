<template>
    <v-sheet
        :color="sheetColor"
        class="my-1 rh-clickable rounded"
        :elevation="elevation"
        @mouseenter="hovered = true"
        @mouseleave="hovered = false"
        @click="navigateToBarracksHero"
    >
        <v-row no-gutters align="center">
            <v-col cols="4" md="3" class="pt-1 px-1">
                <v-row no-gutters>
                    <v-col cols="6">
                        <HeroRaceIcon :hero-race="heroRace"></HeroRaceIcon>
                    </v-col>
                    <v-col cols="6">
                        <HeroClassIcon :hero-class="heroClass"></HeroClassIcon>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="6" md="7">
                <v-row align="center" justify="center">
                    <span class="title font-weight-regular">{{hero.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="2" :class="[$vuetify.breakpoint.name === 'xs' ? '' : 'pa-1']">
                <CombatPositionIcon :combat-position-id="hero.combatPositionID" :attacker-mode="true" :tool-tip="false"></CombatPositionIcon>
            </v-col>
        </v-row>
        <v-row no-gutters align="center">
            <v-col cols="4" md="3">
                <v-sheet class="pt-2 ma-1 rounded" style="background-image: linear-gradient(to bottom right, #393142, #5e526b , #393142)">
                    <HeroGearSVG :hero="hero"></HeroGearSVG>
                </v-sheet>
            </v-col>
            <v-col cols="8" md="9">
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
    import * as routerHelpers from "../../../helpers/routerHelpers"
    import SvgIconSheet from "../global/SvgIconSheet";
    import HeroGearSVG from "./gear/HeroGearSVG";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import Hero from "../../../models/Hero";

    import {mapGetters} from 'vuex';
    import RelativeMeasurableBar from "./RelativeMeasurableBar";
    import HeroClassIcon from "../../icons/heroClasses/HeroClassIcon";
    import HeroRaceIcon from "../../icons/heroRaces/HeroRaceIcon";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";

    export default {
        name: "HeroSummaryPanel",
        components: {
            CombatPositionIcon,
            HeroRaceIcon,
            HeroClassIcon,
            RelativeMeasurableBar,
            PlayerSpiritSummaryPanel,
            HeroGearSVG,
            SvgIconSheet
        },
        props: {
            hero: {
                type: Hero,
                require: true
            }
        },
        data() {
            return {
                hovered: false
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
                '_measurableTypeByName',
                '_heroRaceByID',
                '_heroClassByID'
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
                return routerHelpers.getBarracksHeroRoute(this.hero, this.$route);
            },
            heroRace() {
                return this._heroRaceByID(this.hero.heroRaceID);
            },
            heroClass() {
                return this._heroClassByID(this.hero.heroClassID);
            },
            elevation() {
                return this.hovered ? 24 : 4;
            },
            sheetColor() {
                return this.hovered ? '#6f808c' : '#5c707d';
            }
        }
    }
</script>

<style scoped>

</style>

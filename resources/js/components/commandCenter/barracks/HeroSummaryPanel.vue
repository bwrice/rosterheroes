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
                        <template v-if="hero.playerSpirit">
                            <PlayerSpiritSummaryPanel :player-spirit="hero.playerSpirit"></PlayerSpiritSummaryPanel>
                        </template>
                        <template v-else>
                            <span>Empty</span>
                        </template>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12" class="px-1 pb-1">
                        <v-progress-linear
                            color="#c26161"
                            height="10"
                            :value="healthValue"
                        >
                            <template v-slot="{ value }">
                                <span class="caption font-weight-bold">{{ Math.ceil(value) }}</span>
                            </template>
                        </v-progress-linear>
                        <v-progress-linear
                            color="#ded337"
                            height="10"
                            value="80"
                        >
                            <template v-slot="{ value }">
                                <span class="caption font-weight-bold">{{ Math.ceil(value) }}</span>
                            </template>
                        </v-progress-linear>
                        <v-progress-linear
                            color="#43a1e8"
                            height="10"
                            value="60"
                        >
                            <template v-slot="{ value }">
                                <span class="caption font-weight-bold">{{ Math.ceil(value) }}</span>
                            </template>
                        </v-progress-linear>
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

    export default {
        name: "HeroSummaryPanel",
        components: {PlayerSpiritSummaryPanel, HeroGearSVG, SvgIconSheet},
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
            healthValue() {
                let squadHighHealthAmount = this._squadHighMeasurable('health');
                if (! squadHighHealthAmount) {
                    return 0;
                }
                let heroHealthAmount = this.hero.getMeasurableByType('health').buffedAmount;
                console.log(heroHealthAmount, squadHighHealthAmount);
                return Math.ceil((heroHealthAmount/squadHighHealthAmount) * 100);
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

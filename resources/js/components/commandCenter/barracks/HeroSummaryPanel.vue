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
        </v-row>
    </v-sheet>
</template>

<script>
    import SvgIconSheet from "../global/SvgIconSheet";
    import HeroGearSVG from "./gear/HeroGearSVG";
    export default {
        name: "HeroSummaryPanel",
        components: {HeroGearSVG, SvgIconSheet},
        props: {
            hero: {
                type: Object,
                require: true
            }
        },
        methods: {
            navigateToBarracksHero() {
                this.$router.push(this.barracksHeroRoute);
            }
        },
        computed: {
            barracksHeroRoute() {
                let squadSlugParam = this.$route.params.squadSlug;
                return {
                    name: 'barracks-hero',
                    params: {
                        squadSlug: squadSlugParam,
                        heroSlug: this.hero.slug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

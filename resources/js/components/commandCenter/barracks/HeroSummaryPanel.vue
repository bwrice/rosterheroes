<template>
    <v-sheet
        color="rgba(255, 255, 255, 0.15)"
        class="my-1"
        elevation="5"
    >
        <v-row no-gutters align="center">
            <v-col cols="4">
                <v-row no-gutters>
                    <v-col cols="6">
                        <SvgIconSheet
                            :color="'#C8DDE0'"
                            :svg-icon="hero.heroRace.icon"
                            :classes-object="{'pa-1': true}"
                        >
                        </SvgIconSheet>
                    </v-col>
                    <v-col cols="6">
                        <SvgIconSheet
                            :color="'#C8DDE0'"
                            :svg-icon="hero.heroClass.icon"
                            :classes-object="{'pa-1': true}"
                        >
                        </SvgIconSheet>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="6">
                <v-row align="center" justify="center">
                    <span class="subtitle-1">{{hero.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="2">
                <SvgIconSheet :svg-icon="hero.combatPosition.icon">
                </SvgIconSheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import SvgIconSheet from "../global/SvgIconSheet";
    export default {
        name: "HeroSummaryPanel",
        components: {SvgIconSheet},
        props: {
            hero: {
                type: Object,
                require: true
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

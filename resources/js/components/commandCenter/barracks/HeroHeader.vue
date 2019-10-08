<template>
    <v-card>
        <v-card-text>
            <v-row no-gutters align="center">
                <v-col cols="4">
                    <v-row no-gutters>
                        <v-col cols="6" class="py-0">
                            <HeroRaceIcon :hero-race-id="hero.heroRaceID"></HeroRaceIcon>
                        </v-col>
                        <v-col cols="6">
                            <HeroClassIcon :hero-class-id="hero.heroClassID"></HeroClassIcon>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="6"></v-col>
                <v-col cols="2"></v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
    import BarracksHero from "../../../models/BarracksHero";
    import HeroRaceIcon from "../global/HeroRaceIcon";
    import HeroClassIcon from "../global/HeroClassIcon";

    export default {
        name: "HeroHeader",
        components: {HeroClassIcon, HeroRaceIcon},
        props: {
            hero: {
                type: BarracksHero,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-card class="py-3" style="background-image: linear-gradient(to bottom right, #524c59, #7c7287 , #524c59 )">
        <HeroGearSVG v-if="barracksHeroFromRoute" :hero="barracksHeroFromRoute"></HeroGearSVG>
    </v-card>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";

    import HeroGearSVG from "./HeroGearSVG";
    export default {
        name: "HeroGearCard",
        components: {HeroGearSVG},
        mixins: [
            barracksHeroMixin
        ],
        computed: {
            ...mapGetters([
                '_barracksHeroes'
            ]),
        }
    }
</script>

<style scoped>

</style>

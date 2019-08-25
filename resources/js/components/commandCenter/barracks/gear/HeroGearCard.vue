<template>
    <v-card class="py-3">
        <HeroGearSVG></HeroGearSVG>
    </v-card>
</template>

<script>
    import {mapGetters} from 'vuex';

    import HeroGearSVG from "./HeroGearSVG";
    export default {
        name: "HeroGearCard",
        components: {HeroGearSVG},
        computed: {
            ...mapGetters([
                '_barracksHeroes'
            ]),
            hero() {
                let heroSlug = this.$route.params.heroSlug;
            }
        }
    }
</script>

<style scoped>

</style>

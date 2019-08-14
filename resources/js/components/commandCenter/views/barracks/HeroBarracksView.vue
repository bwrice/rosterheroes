<template>
    <v-flex class="xs12">
        <v-card>
            <v-card-title>{{ hero.name }}</v-card-title>
        </v-card>
    </v-flex>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "HeroView",
        computed: {
            ...mapGetters([
                '_barracksHeroes'
            ]),
            hero() {

                let self = this;
                let hero = this._barracksHeroes.find(function (hero) {
                    return hero.slug === self.$route.params.heroSlug;
                });

                if (hero) {
                    return hero;
                }
                return {
                    'name': '',
                    'measurables': []
                }
            }
        }
    }
</script>

<style scoped>

</style>

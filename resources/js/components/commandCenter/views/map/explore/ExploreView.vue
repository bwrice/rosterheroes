<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-layout>
            <v-flex class="xs12">
                <router-view></router-view>
            </v-flex>
        </v-layout>
    </v-flex>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    export default {
        name: "ExploreView",

        watch:{
            $route (to) {
                // this updates continent if user hits back/forward through browser
                if (to.params.continentSlug !== this._continent.slug) {
                    this.setContinentBySlug(to.params.continentSlug);
                }
            }
        },

        methods: {
            ...mapActions([
                'setContinentBySlug',
            ])
        },
        computed: {
            ...mapGetters([
                '_continent',
            ]),
        }
    }
</script>

<style scoped>

</style>

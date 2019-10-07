<template>
    <v-list>
        <v-list-group
                value="true"
        >
            <v-list-item slot="activator">
                <v-list-item-title>Heroes</v-list-item-title>
            </v-list-item>
<!--            <v-divider></v-divider>-->

<!--            <v-list-item-->
<!--                    v-for="hero in _squad.heroes"-->
<!--                    :key="hero.uuid"-->
<!--                    :to="heroRoute(hero)"-->
<!--                    ripple-->
<!--                    active-class="accent&#45;&#45;text"-->
<!--            >-->
<!--                <v-list-item-title v-text="hero.name"></v-list-item-title>-->
<!--            </v-list-item>-->
        </v-list-group>
    </v-list>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: "BarracksNavigationDrawer",

        computed: {
            ...mapGetters([
                '_squad',
            ])
        },
        methods: {
            heroRoute: function(hero) {
                return '/command-center/' + this._squad.slug + '/barracks/hero/' + hero.slug;
            }
        }
    }
</script>

<style scoped>

</style>

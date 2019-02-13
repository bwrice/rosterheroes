<template>
    <v-list>
        <v-list-group
                value="true"
        >
            <v-list-tile slot="activator">
                <v-list-tile-title>Heroes</v-list-tile-title>
            </v-list-tile>
            <v-divider></v-divider>

            <v-list-tile
                    v-for="hero in _squad.heroes" :key="hero.uuid"
                    :to="heroRoute(hero)"
            >
                <v-list-tile-title v-text="hero.name"></v-list-tile-title>
            </v-list-tile>
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
            heroRoute: (hero) => {
                return 'hero/' + hero.slug;
            }
        }
    }
</script>

<style scoped>

</style>
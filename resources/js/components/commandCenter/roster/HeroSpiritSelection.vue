<template>
    <v-card>
        <h3>{{hero.name}}</h3>
        <v-btn v-on:click="unFocus">Cancel</v-btn>
        <v-data-iterator
                :items="this.playerSpirits"
                content-tag="v-layout"
                hide-actions
                row
                wrap
        >
            <template v-slot:header>
                <v-toolbar
                        class="mb-2"
                        color="#FFC747"
                        dark
                        flat
                >
                    <v-toolbar-title>Select Player Spirit</v-toolbar-title>
                </v-toolbar>
            </template>
            <template v-slot:item="props">
                <v-flex
                        fluid
                        py-1
                        xs12
                >
                    <v-card elevation="5">
                        <v-card-title class="subheading font-weight-bold">
                            {{ props.item.playerName }} {{props.item.gameDescription}}
                        </v-card-title>

                        <v-divider></v-divider>

                        <v-list dense>
                            <v-list-tile>
                                <v-list-tile-content>Essence Cost:</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ props.item.essence_cost }}</v-list-tile-content>
                            </v-list-tile>

                            <v-list-tile>
                                <v-list-tile-content>Energy:</v-list-tile-content>
                                <v-list-tile-content class="align-end">{{ props.item.energy }}</v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-card>
                </v-flex>
            </template>
        </v-data-iterator>
    </v-card>
</template>

<script>
    import { mapActions } from 'vuex';
    import { mapGetters } from 'vuex';

    export default {
        name: "HeroSpiritSelection",
        props: ['hero'],

        mounted() {
            this.updatePlayerSpiritsPool();
        },

        data: function() {
            return {
                playerSpirits: [],
                rowsPerPageItems: [4, 8, 12],
                pagination: {
                    rowsPerPage: 4
                },
                items: [
                    {
                        name: 'Frozen Yogurt',
                        calories: 159,
                        fat: 6.0,
                        carbs: 24,
                        protein: 4.0,
                        sodium: 87,
                        calcium: '14%',
                        iron: '1%'
                    },
                    {
                        name: 'Ice cream sandwich',
                        calories: 237,
                        fat: 9.0,
                        carbs: 37,
                        protein: 4.3,
                        sodium: 129,
                        calcium: '8%',
                        iron: '1%'
                    },
                    {
                        name: 'Eclair',
                        calories: 262,
                        fat: 16.0,
                        carbs: 23,
                        protein: 6.0,
                        sodium: 337,
                        calcium: '6%',
                        iron: '7%'
                    },
                    {
                        name: 'Cupcake',
                        calories: 305,
                        fat: 3.7,
                        carbs: 67,
                        protein: 4.3,
                        sodium: 413,
                        calcium: '3%',
                        iron: '8%'
                    }
                ]
            }
        },

        methods: {
            ...mapActions([
                'setRosterFocusedHero',
                'setPlayerSpiritsPool'
            ]),
            unFocus: function() {
                this.setRosterFocusedHero(null);
            },
            async updatePlayerSpiritsPool() {
                this.playerSpirits = await this._currentWeek.playerSpirits().$get();
            }
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_currentWeek'
            ])
        },
    }
</script>

<style scoped>

</style>
<template>
    <v-card>
        <h3>{{hero.name}}</h3>
        <v-btn v-on:click="unFocus">Cancel</v-btn>
        <template v-for="(playerSpirit, uuid) in this.playerSpirits">
            <v-card>
                <v-card-title>
                    {{playerSpirit.playerName}} ({{playerSpirit.essence_cost}})
                </v-card-title>
            </v-card>
        </template>
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
                playerSpirits: []
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
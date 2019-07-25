<template>
    <v-card>
        <h3>{{hero.name}}</h3>
        <v-btn v-on:click="unFocus">Cancel</v-btn>
        <template v-if="hero.playerSpiritObject">
            <PlayerSpiritPanel :player-spirit="hero.playerSpiritObject">
                <template v-slot:spirit-actions>
                    <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpiritObject"></RemoveSpiritButton>
                </template>
            </PlayerSpiritPanel>
        </template>
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
                    <PlayerSpiritPanel :player-spirit="props.item">
                        <template v-slot:spirit-actions>
                            <AddSpiritButton :hero="hero" :player-spirit="props.item"></AddSpiritButton>
                        </template>
                    </PlayerSpiritPanel>
                </v-flex>
            </template>
        </v-data-iterator>
    </v-card>
</template>

<script>
    import { mapActions } from 'vuex';
    import { mapGetters } from 'vuex';

    import PlayerSpiritPanel from './PlayerSpiritPanel';
    import AddSpiritButton from "./AddSpiritButton";
    import RemoveSpiritButton from "./RemoveSpiritButton";

    export default {
        name: "HeroSpiritSelection",
        props: ['hero'],

        components: {
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },

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
                this.playerSpirits = await this._currentWeek.playerSpirits().where('hero-race', this.hero.heroRace.name).$get();
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
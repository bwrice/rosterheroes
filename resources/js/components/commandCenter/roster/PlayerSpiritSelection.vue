<template>
    <v-card>
        <h3>{{hero.name}}</h3>
        <v-btn v-on:click="unFocus">Cancel</v-btn>

    </v-card>
</template>

<script>
    import { mapActions } from 'vuex';
    import { mapGetters } from 'vuex';

    export default {
        name: "PlayerSpiritSelection",
        props: ['hero'],

        async mounted() {

            this.getPlayerSpirits();
        },

        methods: {
            ...mapActions([
                'setRosterFocusedHero',
                'setPlayerSpiritsPool'
            ]),
            unFocus: function() {
                this.setRosterFocusedHero(null);
            },
            getPlayerSpirits() {
                let self = this;
                axios.get('/api/v1/week/' + self._currentWeek.uuid + '/player-spirits')
                    .then(function (response) {
                        self.setPlayerSpiritsPool(response.data.data);
                    }).catch(function (error) {
                    console.log("ERROR!");
                    console.log(error);
                });
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
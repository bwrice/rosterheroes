<template>
    <v-btn v-on:click="removeSpirit" class="error" :disabled="this.pending">
        Remove
    </v-btn>
</template>

<script>

    import { mapGetters } from 'vuex';
    import { mapActions } from 'vuex';

    export default {
        name: "RemoveSpiritButton",
        props: ['playerSpirit', 'hero'],

        data: function() {
            return {
                pending: false
            }
        },

        computed: {
            ...mapGetters([
                '_rosterFocusedHero'
            ])
        },

        methods: {
            ...mapActions([
                'updateHero',
                'setRosterFocusedHero',
                'snackBarSuccess',
                'snackBarError'
            ]),
            removeSpirit: function () {
                this.pending = true;
                axios.delete('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then((response) => {
                        this.pending = false;
                        let heroResponse = response.data.data;
                        this.updateHero(heroResponse);
                        if (this._rosterFocusedHero) {
                            this.setRosterFocusedHero(heroResponse);
                        }
                        this.snackBarSuccess('Hero Updated');
                    }).catch((error) => {
                    this.pending = false;
                    // TODO: add Errors class to snackBar store and handle there
                    if (error.response && error.response.data.errors.roster) {
                        this.snackBarError(error.response.data.errors.roster[0]);
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>
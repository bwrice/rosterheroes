<template>
    <v-btn v-on:click="addSpirit" class="success" :disabled="this.pending">
        Add
    </v-btn>
</template>

<script>

    import { mapActions } from 'vuex';

    export default {
        name: "AddSpiritButton",
        props: ['playerSpirit', 'hero'],

        data: function() {
            return {
                pending: false
            }
        },

        methods: {
            ...mapActions([
                'updateHero',
                'setRosterFocusedHero',
                'snackBarSuccess',
                'snackBarError'
            ]),
            addSpirit: function() {
                this.pending = true;
                axios.post('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then((response) => {

                    this.pending = false;
                    let heroResponse = response.data.data;
                    this.updateHero(heroResponse);
                    this.setRosterFocusedHero(heroResponse);
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
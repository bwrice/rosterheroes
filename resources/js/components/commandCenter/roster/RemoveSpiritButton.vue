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
                'setRosterFocusedHero'
            ]),
            removeSpirit: function () {
                this.pending = true;
                axios.delete('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then((response) => {
                        console.log("Response Data");
                        console.log(response.data);
                        this.pending = false;
                        let heroResponse = response.data.data;
                        this.updateHero(heroResponse);
                        if (this._rosterFocusedHero) {
                            this.setRosterFocusedHero(heroResponse);
                        }
                    }).catch((error) => {
                    console.log("ERROR!");
                    console.log(error);
                    this.pending = false;
                });
            }
        }
    }
</script>

<style scoped>

</style>
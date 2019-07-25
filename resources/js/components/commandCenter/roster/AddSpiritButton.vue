<template>
    <v-btn v-on:click="addSpirit" class="success" :disabled="this.pending">
        Add
    </v-btn>
</template>

<script>

    import { mapGetters } from 'vuex';
    import { mapActions } from 'vuex';

    export default {
        name: "AddSpiritButton",
        props: ['playerSpirit', 'hero'],

        data: function() {
            return {
                pending: false
            }
        },
        computed: {
            ...mapGetters([
                '_squad'
            ])
        },
        methods: {
            ...mapActions([
                'updateHero'
            ]),
            addSpirit: function() {
                this.pending = true;
                axios.post('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then((response) => {
                    console.log("Response Data");
                    console.log(response.data);
                    this.pending = false;
                    this.updateHero(response.data.data);
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
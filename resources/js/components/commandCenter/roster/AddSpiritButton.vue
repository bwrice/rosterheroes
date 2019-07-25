<template>
    <v-btn v-on:click="addSpirit" class="success" :disabled="this.pending">
        Add
    </v-btn>
</template>

<script>
    export default {
        name: "AddSpiritButton",
        props: ['playerSpirit', 'hero'],

        data: function() {
            return {
                pending: false
            }
        },

        methods: {
            addSpirit: function() {
                let self = this;
                self.pending = true;
                axios.post('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then(function (response) {
                    console.log("Response Data");
                    console.log(response.data);
                    self.pending = false;
                }).catch(function (error) {
                    console.log("ERROR!");
                    console.log(error);
                    self.pending = false;
                });
            }
        }
    }
</script>

<style scoped>

</style>
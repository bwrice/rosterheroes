<template>
    <v-btn v-on:click="addSpirit" class="success">
        Add
    </v-btn>
</template>

<script>
    export default {
        name: "AddSpiritButton",
        props: ['playerSpirit', 'hero'],
        methods: {
            addSpirit: function() {
                let self = this;
                axios.post('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then(function (response) {
                    console.log("Response Data");
                    console.log(response.data);
                }).catch(function (error) {
                    console.log("ERROR!");
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>

</style>
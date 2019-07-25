<template>
    <v-btn v-on:click="removeSpirit" class="error">
        Remove
    </v-btn>
</template>

<script>
    export default {
        name: "RemoveSpiritButton",
        props: ['playerSpirit', 'hero'],
        methods: {
            removeSpirit: function () {
                axios.delete('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
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
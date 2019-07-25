<template>
    <v-btn v-on:click="removeSpirit" class="error" :disabled="this.pending">
        Remove
    </v-btn>
</template>

<script>
    export default {
        name: "RemoveSpiritButton",
        props: ['playerSpirit', 'hero'],

        data: function() {
            return {
                pending: false
            }
        },

        methods: {
            removeSpirit: function () {
                this.pending = true;
                axios.delete('/api/v1/heroes/' + this.hero.uuid + '/player-spirit/' + this.playerSpirit.uuid)
                    .then((response) => {
                        console.log("Response Data");
                        console.log(response.data);
                        this.pending = false;
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
<template>
    <v-btn v-on:click="addSpirit">
        Add
    </v-btn>
</template>

<script>
    export default {
        name: "AddSpiritButton",
        props: ['playerSpirit', 'hero'],
        methods: {
            addSpirit: function() {
                alert(this.playerSpirit.player.first_name);
            }
        }
    }
</script>

<style scoped>

</style>
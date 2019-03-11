<template>
    <v-card>
        <h3>{{hero.name}}</h3>
    </v-card>
</template>

<script>
    export default {
        name: "HeroRosterCard",
        props: ['hero']
    }
</script>

<style scoped>

</style>
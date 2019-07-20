<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
        </v-card-title>
        <div v-if="hero.playerSpirit">
            Filled
        </div>
        <div v-else>
            Empty
        </div>
    </v-card>
</template>

<script>
    export default {
        name: "HeroRosterCard",
        props: ['hero'],
    }
</script>

<style scoped>

</style>
<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
        </v-card-title>
        <slot name="main">

        </slot>
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
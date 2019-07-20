<template>
    <hero-roster-card :hero="hero">
        <template v-slot:main>
            Empty
        </template>
    </hero-roster-card>
</template>

<script>
    // TODO: Delete?
    export default {
        name: "FilledHeroRosterCard",
        props: ['hero']
    }
</script>

<style scoped>

</style>
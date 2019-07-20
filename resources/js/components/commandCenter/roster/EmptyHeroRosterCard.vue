<template>
    <hero-roster-card :hero="hero">
        <template v-slot:main>
            Empty
        </template>
    </hero-roster-card>
</template>

<script>
    // TODO: Delete?
    import HeroRosterCard from './HeroRosterCard';

    export default {
        name: "EmptyHeroRosterCard",
        props: ['hero'],
        components: {
            HeroRosterCard
        }
    }
</script>

<style scoped>

</style>
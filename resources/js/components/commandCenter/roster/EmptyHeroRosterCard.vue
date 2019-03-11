<template>
    <hero-roster-card :hero="hero">

    </hero-roster-card>
</template>

<script>
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
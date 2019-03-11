<template>
    <RhSquadRoster></RhSquadRoster>
</template>

<script>

    import SquadRoster from '../../roster/SquadRoster';

    export default {
        name: "RosterMain",
        components: {
           RhSquadRoster: SquadRoster
        }
    }
</script>

<style scoped>

</style>
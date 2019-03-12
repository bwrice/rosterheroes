<template>
    <v-container class="pa-2">
        <RhSquadRoster></RhSquadRoster>
    </v-container>
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
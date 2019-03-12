<template>
    <v-card>
        <v-layout row wrap>
            <PositionIcon></PositionIcon>
            <h3>{{hero.name}}</h3>
        </v-layout>
    </v-card>
</template>

<script>
    import PositionIcon from '../../icons/PositionIcon';

    export default {
        name: "HeroRosterCard",
        props: ['hero'],
        components: {
            PositionIcon
        }
    }
</script>

<style scoped>

</style>
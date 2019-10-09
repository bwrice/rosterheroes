<template>
    <BaseAppBar :app-bar-title="_squad.name"></BaseAppBar>
</template>

<script>
    import BaseAppBar from "./BaseAppBar";
    import {mapGetters} from 'vuex';
    export default {
        name: "SquadAppBar",
        components: {BaseAppBar},
        computed: {
            ...mapGetters([
                '_squad'
            ])
        }
    }
</script>

<style scoped>

</style>

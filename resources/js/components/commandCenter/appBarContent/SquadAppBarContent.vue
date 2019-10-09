<template>
    <v-toolbar-title>
        {{ _squad.name }}
    </v-toolbar-title>
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

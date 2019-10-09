<template>
    <v-toolbar-title>
        {{ _squad.name }}
    </v-toolbar-title>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
        name: "SquadAppBar",
        computed: {
            ...mapGetters([
                '_squad'
            ])
        }
    }
</script>

<style scoped>

</style>

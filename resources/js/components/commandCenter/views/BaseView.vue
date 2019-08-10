<template>
    <v-container class="fluid pa-1">
        <v-layout>
            <router-view></router-view>
        </v-layout>
        <v-overlay :value="_overlay.show">
            <v-progress-circular
                size="64"
                indeterminate
                color="primary"
            ></v-progress-circular>
        </v-overlay>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "BaseView",
        computed: {
            ...mapGetters([
                '_overlay'
            ])
        }
    }
</script>

<style scoped>

</style>

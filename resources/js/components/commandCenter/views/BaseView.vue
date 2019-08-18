<template>
    <v-container>
        <v-row no-gutters>
            <router-view></router-view>
        </v-row>
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

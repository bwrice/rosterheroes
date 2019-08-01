<template>
    <BaseView></BaseView>
</template>

<script>

    import BaseView from "../BaseView";

    import { mapActions } from 'vuex'

    export default {
        name: "Map",
        components: {BaseView},
        mounted: function() {
            this.updateProvinces();
        },
        methods: {
            ...mapActions([
                'updateProvinces'
            ])
        },
    }
</script>

<style scoped>

</style>
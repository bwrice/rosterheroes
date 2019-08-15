<template>
    <BaseView></BaseView>
</template>

<script>
    import {mapActions} from 'vuex';
    import BaseView from "../BaseView";
    export default {
        name: "Barracks",
        components: {BaseView},
        mounted() {
            this.updateBarracksHeroes(this.$route.params.squadSlug);
        },
        methods: {
            ...mapActions([
                'updateBarracksHeroes'
            ])
        },
    }
</script>

<style scoped>

</style>

<template>
    <div>
        <h1 class="mx-4">TODO: Barracks</h1>
    </div>
</template>

<script>

    import {mapActions} from 'vuex';

    export default {
        name: "BarracksMain",
        created() {
            console.log("Barracks Created!");
        },
        mounted() {
            this.updateBarracksHeroes(this.$route.params.squadSlug);
        },
        methods: {
            ...mapActions([
                'updateBarracksHeroes'
            ])
        }
    }
</script>

<style scoped>

</style>

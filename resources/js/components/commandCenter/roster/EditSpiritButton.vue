<template>
    <v-btn :to="heroRoute" class="primary">Edit</v-btn>
</template>

<script>
    export default {
        name: "EditSpiritButton",
        props: ['hero'],
        computed: {
            heroRoute: function() {
                let squadSlug = this.$route.params.squadSlug;
                return '/command-center/' + squadSlug + '/roster/hero/' + this.hero.slug;
            }
        }
    }
</script>

<style scoped>

</style>

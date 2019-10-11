<template>
    <v-btn :to="heroRoute" small class="ma-1" color="accent darken-1">
        <v-icon>edit</v-icon>
    </v-btn>
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

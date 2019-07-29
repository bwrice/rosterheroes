<template>
    <v-btn :to="heroRoute()" class="primary">Edit</v-btn>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        name: "EditSpiritButton",
        props: ['hero'],
        computed: {
            ...mapGetters([
                '_squad',
            ])
        },
        methods: {
            heroRoute: function() {
                return '/command-center/' + this._squad.slug + '/roster/hero/' + this.hero.slug;
            }
        }
    }
</script>

<style scoped>

</style>
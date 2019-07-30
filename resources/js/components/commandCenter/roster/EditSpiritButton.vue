<template>
    <v-btn v-on:click="showHeroRosterView()" class="primary">Edit</v-btn>
</template>

<script>
    import { mapGetters } from 'vuex';
    import { mapActions } from 'vuex';

    export default {
        name: "EditSpiritButton",
        props: ['hero'],
        methods: {
            ...mapActions([
                'setHero',
                'updatePlayerSpiritsPool'
            ]),
            showHeroRosterView() {
                this.setHero(this.hero);
                this.updatePlayerSpiritsPool();
                this.$router.push(this.heroRoute);
            }
        },
        computed: {
            ...mapGetters([
                '_squad',
            ]),
            heroRoute: function() {
                return '/command-center/' + this._squad.slug + '/roster/hero/' + this.hero.slug;
            }
        }
    }
</script>

<style scoped>

</style>
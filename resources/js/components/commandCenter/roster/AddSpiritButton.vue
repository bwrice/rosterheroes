<template>
    <v-btn v-on:click="addSpirit" class="success" :disabled="this.pending">
        Add
    </v-btn>
</template>

<script>

    import { mapActions } from 'vuex';

    export default {
        name: "AddSpiritButton",
        props: ['playerSpirit', 'hero'],

        data: function() {
            return {
                pending: false
            }
        },

        methods: {
            ...mapActions([
                'updateHero',
                'setRosterFocusedHero',
                'snackBarSuccess',
                'snackBarError',
                'addSpiritToHero'
            ]),
            async addSpirit() {

                this.pending = true;

                await this.addSpiritToHero({
                    heroSlug: this.hero.slug,
                    spiritUuid: this.playerSpirit.uuid
                });

                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>

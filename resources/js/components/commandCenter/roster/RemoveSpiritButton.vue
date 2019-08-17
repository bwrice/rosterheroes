<template>
    <v-btn v-on:click="removeSpirit" class="error" :disabled="this.pending">
        Remove
    </v-btn>
</template>

<script>

    import { mapActions } from 'vuex';

    export default {
        name: "RemoveSpiritButton",
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
                'removeSpiritFromHero'
            ]),
            async removeSpirit() {
                this.pending = true;
                await this.removeSpiritFromHero({
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

<template>
    <v-btn
        @click="addSpirit"
        small
        color="primary"
        :disabled="disabled"
    >
        embody
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
        },
        computed: {
            disabled() {
                return this.pending || (! this.hero.slug);
            }
        }
    }
</script>

<style scoped>

</style>

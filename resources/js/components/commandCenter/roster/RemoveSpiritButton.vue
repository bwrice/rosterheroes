<template>
    <v-btn @click="removeSpirit" small class="ma-1" color="error" :disabled="this.pending">
        <v-icon>remove</v-icon>
    </v-btn>
</template>

<script>

    import { mapActions } from 'vuex';
    import PlayerSpirit from "../../../models/PlayerSpirit";
    import Hero from "../../../models/Hero";

    export default {
        name: "RemoveSpiritButton",
        props: {
            playerSpirit: {
                type: PlayerSpirit,
                required: true
            },
            hero: {
                type: Hero,
                required: true
            }
        },

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

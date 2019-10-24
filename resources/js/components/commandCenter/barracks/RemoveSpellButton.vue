<template>
    <v-btn
        class="my-2"
        block color="error"
        :disabled="pending"
        @click="removeSpell"
    >
        Remove Spell
    </v-btn>
</template>

<script>
    import Spell from "../../../models/Spell";
    import Hero from "../../../models/Hero";
    import {mapActions} from 'vuex';

    export default {
        name: "RemoveSpellButton",
        props: {
            hero: {
                type: Hero,
                required: true
            },
            spell: {
                type: Spell,
                required: true
            }
        },
        data() {
            return {
                pending: false
            }
        },
        methods: {
            ...mapActions([
                'removeSpellOnHero'
            ]),
            async removeSpell() {
                this.pending = true;
                await this.removeSpellOnHero({
                    hero: this.hero,
                    spell: this.spell
                });
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>

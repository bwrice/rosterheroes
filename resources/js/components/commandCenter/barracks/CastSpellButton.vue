<template>
    <v-btn
        class="my-2"
        block color="primary"
        :disabled="pending"
        @click="castSpell"
    >
        Cast Spell
    </v-btn>
</template>

<script>
    import Spell from "../../../models/Spell";
    import Hero from "../../../models/Hero";
    import {mapActions} from 'vuex';

    export default {
        name: "CastSpellButton",
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
                'castSpellOnHero'
            ]),
            async castSpell() {
                this.pending = true;
                await this.castSpellOnHero({
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

<template>
    <v-card>
        <v-card-title>Spells</v-card-title>
        <v-card-text>
            <span class="subtitle-1">Spells Casted</span>
            <SpellPanelIterator :spells="hero.spells" :items-per-page="4">
                <template v-slot:after-boosts="panelProps">
                    <RemoveSpellButton :hero="hero" :spell="panelProps.spell"></RemoveSpellButton>
                </template>
            </SpellPanelIterator>
            <span class="subtitle-1">Spell Library</span>
            <SpellPanelIterator :spells="availableSpells">
                <template v-slot:after-boosts="panelProps">
                    <AddSpellButton :hero="hero" :spell="panelProps.spell"></AddSpellButton>
                </template>
            </SpellPanelIterator>
        </v-card-text>
    </v-card>
</template>

<script>
    import Hero from "../../../models/Hero";
    import SpellPanelIterator from "./SpellPanelIterator";
    import RemoveSpellButton from "./RemoveSpellButton";
    import {mapGetters} from 'vuex';
    import AddSpellButton from "./AddSpellButton";

    export default {
        name: "HeroSpellsCard",
        components: {AddSpellButton, RemoveSpellButton, SpellPanelIterator},
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_spellLibrary'
            ]),
            availableSpells() {
                let spellsInUseIDs = this.hero.spells.map(spell => spell.id);
                return this._spellLibrary.filter(spell => ! spellsInUseIDs.includes(spell.id));
            }
        }
    }
</script>

<style scoped>

</style>

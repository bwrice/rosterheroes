<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-row no-gutters align="center" justify="space-between">
                <v-col cols="6">
                    <span class="title font-weight-thin">SPELLS</span>
                </v-col>
                <v-col cols="6">
                    <v-row no-gutters class="flex-column" align="end" style="color: rgba(255, 255, 255, .8)">
                        <div>
                            <span class="subtitle-1" style="color: rgba(255, 255, 255, .8)">Spell Power: </span>
                            <span class="subtitle-1 font-weight-bold" style="color: rgba(255, 255, 255, .8)">{{hero.spellPower}}</span>
                        </div>
                        <div>
                            <span class="subtitle-1" style="color: rgba(255, 255, 255, .8)">Mana Used: </span>
                            <span class="subtitle-1 font-weight-bold" style="color: rgba(255, 255, 255, .8)">{{hero.manaUsed}}</span>
                        </div>
                    </v-row>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col cols="12">
                    <template v-if="hero.spells.length">
                        <span class="subtitle-1" style="color: rgba(255, 255, 255, .8)">Current Spells on {{hero.name}}</span>
                        <SpellPanelIterator :spells="hero.spells" :items-per-page="4">
                            <template v-slot:after-boosts="panelProps">
                                <RemoveSpellButton :hero="hero" :spell="panelProps.spell"></RemoveSpellButton>
                            </template>
                        </SpellPanelIterator>
                    </template>
                    <template v-else>
                        <span class="subtitle-1" style="color: rgba(255, 255, 255, .8)">No Spells on {{hero.name}}</span>
                    </template>
                    <v-divider class="my-3"></v-divider>
                    <span class="subtitle-1" style="color: rgba(255, 255, 255, .8)">Spell Library</span>
                    <SpellPanelIterator :spells="availableSpells">
                        <template v-slot:after-boosts="panelProps">
                            <CastSpellButton :hero="hero" :spell="panelProps.spell"></CastSpellButton>
                        </template>
                    </SpellPanelIterator>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import Hero from "../../../models/Hero";
    import SpellPanelIterator from "./SpellPanelIterator";
    import RemoveSpellButton from "./RemoveSpellButton";
    import {mapGetters} from 'vuex';
    import CastSpellButton from "./CastSpellButton";

    export default {
        name: "HeroSpellsCard",
        components: {CastSpellButton, RemoveSpellButton, SpellPanelIterator},
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

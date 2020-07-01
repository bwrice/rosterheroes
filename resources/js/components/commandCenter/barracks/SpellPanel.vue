<template>
    <v-sheet color="#615769"
             class="rounded-sm"
             style="margin: 1px 0 1px 0"
    >
        <v-row align="center" justify="center" class="mx-2">
            <span class="subtitle-2 font-weight-light pa-2">{{spell.name}}</span>
            <div class="flex-grow-1"></div>
            <v-btn @click="expanded = ! expanded"
                   fab
                   dark
                   x-small
                   color="rgba(0, 0, 0, .4)"
            >
                <v-icon v-if="expanded">expand_less</v-icon>
                <v-icon v-else>expand_more</v-icon>
            </v-btn>
        </v-row>
        <v-row v-if="expanded" no-gutters>
            <v-col cols="12">
                <v-sheet color="rgba(0, 0, 0, 0.4)" class="mx-2 my-1 px-3 pb-1 pt-1 rounded-sm">
                    <v-row no-gutters class="flex-column" align="start">
                        <span class="subtitle-1 font-weight-bold" style="color: rgba(255, 255, 255, 0.85)">
                            Mana Cost: {{this.spell.manaCost}}
                        </span>
                        <MeasurableBoostDescription v-for="(measurableBoost, measurableTypeID) in spell.measurableBoosts"
                                                    :key="measurableTypeID"
                                                    :measurable-boost="measurableBoost"
                        ></MeasurableBoostDescription>
                        <slot name="after-boosts" :spell="spell">
                            <!-- Slot -->
                        </slot>
                    </v-row>
                </v-sheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import Spell from "../../../models/Spell";
    import MeasurableBoostDescription from "./MeasurableBoostDescription";

    export default {
        name: "SpellPanel",
        components: {MeasurableBoostDescription},
        props: {
            spell: {
                type: Spell,
                required: true
            }
        },
        data() {
            return {
                expanded: false
            }
        }
    }
</script>

<style scoped>

</style>

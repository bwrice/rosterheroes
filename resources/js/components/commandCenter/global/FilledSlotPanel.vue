<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-row no-gutters>
                <v-col cols="12">
                    <v-sheet color="rgba(255,255,255,0.2)"
                             tile
                             style="margin: 1px 0 1px 0"
                    >
                        <v-row align="center" justify="center" class="mx-2">
                            <span class="title font-weight-light pa-2">{{filledSlot.item.name}}</span>
                            <div class="flex-grow-1"></div>
                            <slot name="before-expand" :item="filledSlot.item">
                                <!-- Slot -->
                            </slot>
                            <v-btn @click="expanded = ! expanded"
                                fab
                                dark
                                x-small>
                                <v-icon v-if="expanded">expand_less</v-icon>
                                <v-icon v-else>expand_more</v-icon>
                            </v-btn>
                        </v-row>
                    </v-sheet>
                </v-col>
            </v-row>
            <v-row v-if="expanded" no-gutters>
                <v-col cols="12">
                    <ItemCard :item="filledSlot.item"></ItemCard>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import ItemCard from "./ItemCard";
    import Slot from "../../../models/Slot";
    export default {
        name: "FilledSlotPanel",
        components: {ItemCard},
        props: {
            filledSlot: {
                type: Slot,
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

<template>
    <v-sheet color="#576269"
             tile
             style="margin: 1px 0 1px 0"
    >
        <v-row align="center" justify="center" class="mx-2">
            <span class="subtitle-2 font-weight-light pa-2">{{itemName}}</span>
            <div class="flex-grow-1"></div>
            <slot name="before-expand" :item="item">
                <!-- Slot -->
            </slot>
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
                <ItemCard :item="item" :color="itemCardColor"></ItemCard>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import ItemCard from "./ItemCard";
    import Slot from "../../../models/Slot";
    import Item from "../../../models/Item";
    export default {
        name: "ItemExpandPanel",
        components: {ItemCard},
        props: {
            item: {
                type: Item,
                required: true
            },
            itemNameTruncateExtra: {
                type: Number,
                default: 0
            },
            itemCardColor: {
                type: String
            }
        },
        data() {
            return {
                expanded: false
            }
        },
        computed: {
            itemName() {
                let windowWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
                let maxLength = Math.floor(windowWidth/12);
                maxLength -= this.itemNameTruncateExtra;
                maxLength = Math.min(maxLength, 40);
                return _.truncate(this.item.name, {
                    length: maxLength
                })
            }
        }
    }
</script>

<style scoped>

</style>

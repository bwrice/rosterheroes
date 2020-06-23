<template>
    <v-sheet color="#576269"
             tile
             style="margin: 1px 0 1px 0"
    >
        <v-row ref="row" align="center" justify="center" class="mx-2 text-truncate" no-gutters>
            <span class="subtitle-2 font-weight-light pa-2 d-inline-block text-truncate" :style="[itemNameStyleObject]">{{item.name}}</span>
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
        mounted() {
            let clientWidth = this.$refs.row.clientWidth;
            let itemNameMaxWidth = 250;
            if (clientWidth > 250) {
                itemNameMaxWidth = this.$refs.row.clientWidth - 120;
            }
            this.itemNameStyleObject['max-width'] = itemNameMaxWidth + 'px';
        },
        data() {
            return {
                expanded: false,
                itemNameStyleObject: {
                    'max-width': '100px'
                }
            }
        },
    }
</script>

<style scoped>

</style>

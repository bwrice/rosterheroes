<template>
    <v-sheet color="#576269" style="margin: 1px 0 1px 0" class="py-1">
        <v-row ref="row" align="center" class="mx-2" no-gutters>
            <v-col cols="8">
                <template v-if="item.shopPrice">
                    <v-row no-gutters align="center">
                        <v-col cols="3">
                            <v-row no-gutters align="center">
                                <span class="subtitle-2 font-went-bold pa-2">({{item.shopPriceTruncated}})</span>
                            </v-row>
                        </v-col>
                        <v-col cols="9">
                            <v-row no-gutters align="center">
                                <v-col cols="12" class="text-truncate">
                                    <span class="subtitle-2 font-weight-light pa-2">{{item.name}}</span>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </template>
                <template v-else>
                    <v-row no-gutters align="center">
                        <v-col cols="12" class="text-truncate">
                            <span class="subtitle-2 font-weight-light pa-2">{{item.name}}</span>
                        </v-col>
                    </v-row>
                </template>
            </v-col>
            <v-col cols="4">
                <v-row no-gutters justify="end" align="center">
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
            </v-col>
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
        data() {
            return {
                expanded: false
            }
        }
    }
</script>

<style scoped>

</style>

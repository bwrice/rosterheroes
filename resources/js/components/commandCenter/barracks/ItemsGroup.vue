<template>
    <v-card>
        <v-slide-x-transition mode="out-in">
            <div v-if="focusedItem" style="height: 288px; overflow-y: scroll" :key="'focused'">
                <ItemCard
                    :item="focusedItem"
                    @close="focusedItem = null"
                ></ItemCard>
            </div>
            <div v-else :key="'scroll'">
                <v-virtual-scroll
                    :items="items"
                    height="288"
                    item-height="48"
                    bench="2"
                >
                    <template v-slot:default="{ item }">
                        <ItemSummarySheet
                            :item="item"
                            @viewItem="showItemDetails"
                        >
                            <template v-slot:before-show-icon="{item}">
                                <!-- nested scoped slots -->
                                <slot name="before-show-icon" :item="item">
                                    <!-- slot:before-show-icon -->
                                </slot>
                            </template>
                        </ItemSummarySheet>
                        <v-divider></v-divider>
                    </template>
                </v-virtual-scroll>
            </div>
        </v-slide-x-transition>
    </v-card>
</template>

<script>
    import ItemExpandPanel from "../global/ItemExpandPanel";
    import ItemSummarySheet from "../global/ItemSummarySheet";
    import ItemCard from "../global/ItemCard";
    export default {
        name: "ItemsGroup",
        components: {ItemCard, ItemSummarySheet, ItemExpandPanel},
        props: {
            items: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                focusedItem: null
            }
        },
        methods: {
            showItemDetails (item) {
                this.focusedItem = item;
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-card>
        <v-slide-x-transition mode="out-in">
            <!-- FOCUSED ITEM -->
            <div
                v-if="focusedItem"
                :style="'height:' + groupHeight + 'px;'" style="overflow-y: scroll"
                :key="'focused'"
            >
                <v-row no-gutters justify="center">
                    <v-btn
                        color="primary"
                        @click="focusedItem = null"
                        class="mt-2 mx-2"
                    >
                        <v-icon left>reply</v-icon>
                        {{backButtonText}}
                    </v-btn>
                </v-row>
                <ItemCard
                    :item="focusedItem"
                    class="ma-2"
                ></ItemCard>
            </div>

            <!-- VIRTUAL SCROLL -->
            <div v-else :key="'scroll'">
                <!-- Loading -->
                <div v-if="loading" class="d-flex justify-center align-center flex-column"
                     :style="'height: ' + groupHeight + 'px'">
                    <v-progress-circular
                        :size="70"
                        :width="7"
                        color="primary"
                        indeterminate
                    ></v-progress-circular>
                </div>


                <!-- Empty -->
                <div
                    v-else-if="empty"
                    class="d-flex justify-center align-center flex-column"
                    :style="'height: ' + groupHeight + 'px'">
                    <span
                        class="text-h6 text-lg-h5 text-center ma-4"
                        style="color: rgba(255, 255, 255, 0.8)"
                    >
                        {{emptyMessage}}
                    </span>
                </div>

                <!-- Items Scroll -->
                <v-virtual-scroll
                    :items="items"
                    :height="groupHeight"
                    :item-height="itemHeight"
                    bench="2"
                    v-else-if="items.length > 0"
                    style="overflow-x: hidden;"
                >
                    <template v-slot:default="{ item }">
                        <ItemSummarySheet
                            :item="item"
                            @viewItem="setFocusedItem"
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

                <!-- No Items (Not the same as empty because of potential filtering/searching) -->
                <div v-else class="d-flex justify-center align-center flex-column"
                     :style="'height: ' + groupHeight + 'px'">
                    <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">No Items Found</span>
                    <slot name="after-no-items-message">
                        <!-- slot to add things like "clear-filters" button -->
                    </slot>
                </div>
            </div>
        </v-slide-x-transition>
    </v-card>
</template>

<script>
    import ItemSummarySheet from "./ItemSummarySheet";
    import ItemCard from "./ItemCard";
    export default {
        name: "ItemVirtualScroll",
        components: {ItemCard, ItemSummarySheet},
        props: {
            items: {
                type: Array,
                required: true
            },
            count: {
                type: Number,
                default: 6
            },
            empty: {
                type: Boolean,
                default: false
            },
            emptyMessage: {
                type: String,
                default: 'Empty'
            },
            loading: {
                type: Boolean,
                default: false
            },
            backButtonText: {
                type: String,
                default: 'back'
            }
        },
        data() {
            return {
                focusedItem: null,
                itemHeight: 48
            }
        },
        methods: {
            setFocusedItem(item) {
                this.focusedItem = item;
            }
        },
        computed: {
            groupHeight() {
                return this.count * this.itemHeight;
            }
        }
    }
</script>

<style scoped>

</style>

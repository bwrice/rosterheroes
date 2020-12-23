<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-text-field
                v-model="searchInput"
                clearable
                flat
                solo-inverted
                hide-details
                prepend-inner-icon="search"
                :label="searchLabel"
                dense
                class="mb-1"
            >
            <template v-slot:append-outer>
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            small
                            color="info"
                        >
                            <v-icon left>
                                mdi-filter
                            </v-icon>
                            Filter
                        </v-btn>
                    </template>
                    <v-card color="#363038">
                        <v-card-subtitle>Filter Items</v-card-subtitle>
                        <v-select
                            v-model="selectedItemBaseNames"
                            :items="itemBaseNames"
                            chips
                            label="Item Bases"
                            multiple
                            clearable
                            class="ma-1"
                        >
                            <template v-slot:selection="{ attrs, item, select, selected }">
                                <v-chip
                                    color=""
                                    v-bind="attrs"
                                    :input-value="selected"
                                    close
                                    @click:close="removeItemBaseFilter(item)"
                                >
                                    <strong>{{item}}</strong>
                                </v-chip>
                            </template>
                        </v-select>
                    </v-card>
                </v-menu>
            </template>
            </v-text-field>
        </v-col>
        <v-col cols="12">
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
                            :items="filteredItems"
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
        </v-col>
    </v-row>
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
                focusedItem: null,
                searchInput: '',
                searchLabel: 'Search Wagon',
                selectedItemBaseNames: []
            }
        },
        methods: {
            showItemDetails (item) {
                this.focusedItem = item;
            },
            removeItemBaseFilter(itemBaseName) {
                this.selectedItemBaseNames.splice(this.selectedItemBaseNames.indexOf(itemBaseName), 1)
                this.selectedItemBaseNames = [...this.selectedItemBaseNames]
            }
        },
        computed: {
            itemBaseNames() {
                return this.items.map(function (item) {
                    return item.itemType.itemBase.name;
                }).sort();
            },
            filteredItems() {
                let filteredItems = this.items;
                let baseNames = this.selectedItemBaseNames;
                if (baseNames.length > 0) {
                    filteredItems = filteredItems.filter((item) => baseNames.includes(item.itemType.itemBase.name));
                }
                return filteredItems;
            }
        }
    }
</script>

<style scoped>

</style>

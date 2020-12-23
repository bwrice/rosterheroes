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
                    v-model="filterMenu"
                    offset-y
                    max-width="360"
                    left
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            v-bind="attrs"
                            v-on="on"
                            small
                            color="info"
                        >
                            <v-icon left :color="filterIconColor">
                                mdi-filter
                            </v-icon>
                            Filter
                        </v-btn>
                    </template>
                    <v-card color="#363038" class="pa-2">
                        <v-select
                            v-model="selectedItemBaseNames"
                            :items="itemBaseNames"
                            small-chips
                            label="Item Bases"
                            multiple
                            clearable
                            dense
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
                        <v-select
                            v-model="minQualityName"
                            :items="itemQualityNames"
                            label="Min Quality"
                            clearable
                            dense
                            class="ma-1"
                        >
                        </v-select>
                        <v-select
                            v-model="maxQualityName"
                            :items="itemQualityNames"
                            label="Max Quality"
                            clearable
                            dense
                            class="ma-1"
                        >
                        </v-select>
                        <v-card-actions>
                            <v-btn
                                color="accent darken-1"
                                @click="clearFilters"
                                small
                            >Clear All</v-btn>
                            <v-btn
                                color="primary"
                                @click="filterMenu = false"
                                small
                            >Done</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-menu>
            </template>
            </v-text-field>
        </v-col>
        <v-col cols="12">
            <v-card>
                <v-slide-x-transition mode="out-in">
                    <div v-if="focusedItem" :style="'height:' + groupHeight + 'px;'" style="overflow-y: scroll" :key="'focused'">
                        <ItemCard
                            :item="focusedItem"
                            @close="focusedItem = null"
                        ></ItemCard>
                    </div>
                    <div v-else :key="'scroll'">
                        <div v-if="items.length === 0" class="d-flex justify-center align-center flex-column"
                             :style="'height: ' + groupHeight + 'px'">
                            <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">
                                {{emptyMessage}}
                            </span>
                        </div>
                        <v-virtual-scroll
                            :items="filteredItems"
                            :height="groupHeight"
                            :item-height="itemHeight"
                            bench="2"
                            v-else-if="filteredItems.length > 0"
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
                        <div v-else class="d-flex justify-center align-center flex-column"
                             :style="'height: ' + groupHeight + 'px'">
                            <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">No Items Found</span>
                            <v-btn
                                color="accent darken-1"
                                class="my-2"
                                @click="clearSearchAndFilters"
                            >Clear Filters</v-btn>
                        </div>
                    </div>
                </v-slide-x-transition>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    import * as jsSearch from 'js-search';
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
            },
            count: {
                type: Number,
                default: 6
            },
            searchLabel: {
                type: String,
                default: 'Search Items'
            },
            emptyMessage: {
                type: String,
                default: 'Empty'
            }
        },
        data() {
            return {
                focusedItem: null,
                searchInput: '',
                selectedItemBaseNames: [],
                minQualityName: null,
                maxQualityName: null,
                debounceSearchItems: _.debounce(this.searchItems, 400),
                itemsSearched: [],
                itemHeight: 48,
                filterMenu: false
            }
        },
        created() {
            this.itemsSearched = this.items;
        },
        watch: {
            searchInput(newValue) {
                this.debounceSearchItems(newValue);
            },
            items(newValue) {
                this.searchInput = '';
                this.itemsSearched = newValue;
            }
        },
        methods: {
            searchItems(searchInput) {
                if (searchInput && searchInput.length > 0) {
                    let search = new jsSearch.Search('uuid');
                    search.addIndex(['name']);
                    search.addIndex(['itemType', 'name']);
                    search.addIndex(['itemClass', 'name']);
                    search.addIndex(['material', 'name']);
                    search.addDocuments(this.items);
                    this.itemsSearched = search.search(searchInput);
                } else {
                    this.itemsSearched = this.items;
                }
            },
            showItemDetails (item) {
                this.focusedItem = item;
            },
            removeItemBaseFilter(itemBaseName) {
                this.selectedItemBaseNames.splice(this.selectedItemBaseNames.indexOf(itemBaseName), 1)
                this.selectedItemBaseNames = [...this.selectedItemBaseNames]
            },
            clearSearchAndFilters() {
                this.searchInput = '';
                this.clearFilters();
            },
            clearFilters() {
                this.selectedItemBaseNames = [];
                this.minQualityName = null;
                this.maxQualityName = null;
            }
        },
        computed: {
            itemBaseNames() {
                return this.items.map(function (item) {
                    return item.itemType.itemBase.name;
                }).sort();
            },
            filteredItems() {
                // Start with items already filtered by search
                let filteredItems = this.itemsSearched;
                // Filter based on item bases;
                let baseNames = this.selectedItemBaseNames;
                if (baseNames.length > 0) {
                    filteredItems = filteredItems.filter(item => baseNames.includes(item.itemType.itemBase.name));
                }
                // Filter based on min enchantment quality
                let minQuality = this.minQualityName ? this.itemQualities.find(quality => quality.name === this.minQualityName) : null;
                if (minQuality) {
                    filteredItems = filteredItems.filter(item => item.enchantmentQuality.value >= minQuality.value);
                }
                // Filter based on max enchantment quality
                let maxQuality = this.maxQualityName ? this.itemQualities.find(quality => quality.name === this.maxQualityName) : null;
                if (maxQuality) {
                    filteredItems = filteredItems.filter(item => item.enchantmentQuality.value <= maxQuality.value);
                }
                return filteredItems;
            },
            itemQualities() {
                let enchantmentQualities = this.items.map(item => item.enchantmentQuality);
                return _.uniqBy(enchantmentQualities, (quality) => quality.value).sort((qualityA, qualityB) => qualityA.value - qualityB.value);
            },
            itemQualityNames() {
                return this.itemQualities.map(quality => quality.name);
            },
            filterIconColor() {
                if (this.selectedItemBaseNames.length > 0 || this.minQualityName || this.maxQualityName) {
                    return 'accent';
                }
                return '#fff';
            },
            groupHeight() {
                return this.count * this.itemHeight;
            }
        }
    }
</script>

<style scoped>

</style>

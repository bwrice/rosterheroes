<template>
    <TwoColumnWideLayout>
        <template v-slot:column-one>
            <v-row no-gutters justify="center">
                <span class="rh-op-85" :class="[titleSizeClass, titleFontWeightClass]">{{_shop.name}}</span>
            </v-row>
            <v-row no-gutters>
                <v-col cols="12" sm="8" class="pr-sm-2">
                    <ItemIterator :items="_shopItems" search-label="Search Shop">
                        <template v-slot:before-expand="props">
                            <div class="px-2">
                                <v-btn
                                    x-small
                                    color="success"
                                    @click="handleBuyClick(props.item)"
                                >
                                    buy
                                </v-btn>
                            </div>
                        </template>
                    </ItemIterator>
                </v-col>
                <v-col cols="12" sm="4">
                    <v-row no-gutters>
                        <v-col cols="6" sm="12">
                            <v-text-field
                                outlined
                                clearable
                                type="number"
                                v-model="minValue"
                                :label="'Min Value'"
                                step="25"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6" sm="12">
                            <v-text-field
                                outlined
                                clearable
                                type="number"
                                v-model="maxValue"
                                :label="'Max Value'"
                                step="25"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="6" sm="12">
                            <v-select
                                v-model="selectedItemBases"
                                :items="itemBaseNames"
                                :menu-props="{ maxHeight: '300' }"
                                label="Item Bases"
                                multiple
                                outlined
                                clearable
                            ></v-select>
                        </v-col>
                        <v-col cols="6" sm="12">
                            <v-select
                                v-model="selectedItemClasses"
                                :items="itemClassNames"
                                :menu-props="{ maxHeight: '300' }"
                                label="Item Classes"
                                multiple
                                outlined
                                clearable
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </template>
        <template v-slot:column-two>
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">SELL ITEMS</span>
                </v-col>
                <v-col cols="12">
                    <v-sheet class="my-1 px-2 py-1" color="rgba(255,255,255, 0.25)">
                        <v-row no-gutters align="center" justify="center">
                            <span class="subtitle-1">
                                Gold: {{sellGold.toLocaleString()}}
                            </span>
                            <v-spacer></v-spacer>
                            <v-btn
                                @click="clearItemsToSell"
                                color="error"
                                class="mx-1"
                                :disabled="_itemsToSell.length === 0"
                            >clear</v-btn>
                            <v-btn
                                color="primary"
                                class="mx-1"
                                :disabled="_itemsToSell.length === 0"
                            >sell</v-btn>
                        </v-row>
                    </v-sheet>
                </v-col>
                <v-col cols="12">
                    <ItemIterator
                        :items="_itemsToSell"
                        :with-search="false"
                    >
                        <template v-slot:before-expand="props">
                            <div class="px-2">
                                <RemoveItemToSellButton :item="props.item"></RemoveItemToSellButton>
                            </div>
                        </template>
                    </ItemIterator>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col cols="12">
                    <span class="title font-weight-thin">{{_mobileStorage.mobileStorageRank.name.toUpperCase()}}</span>
                </v-col>
                <v-col cols="12">
                    <ItemIterator
                        :items="filteredMobileStorageItems"
                    >
                        <template v-slot:before-expand="props">
                            <div class="px-2">
                                <AddItemToSellButton :item="props.item"></AddItemToSellButton>
                            </div>
                        </template>
                    </ItemIterator>
                </v-col>
            </v-row>
        </template>
    </TwoColumnWideLayout>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import TwoColumnWideLayout from "../../../layouts/TwoColumnWideLayout";
    import DisplayHeaderText from "../../../global/DisplayHeaderText";
    import ItemIterator from "../../../global/ItemIterator";
    import AddItemToSellButton from "../../../realm/AddItemToSellButton";
    import RemoveItemToSellButton from "../../../realm/RemoveItemToSellButton";
    export default {
        name: "ShopView",
        components: {
            RemoveItemToSellButton,
            AddItemToSellButton,
            ItemIterator,
            DisplayHeaderText,
            TwoColumnWideLayout
        },
        mounted() {
            this.maybeUpdateShop();
        },
        data() {
            return {
                minValue: null,
                maxValue: null,
                selectedItemBases: [],
                itemClassNames: [
                    'Generic',
                    'Enchanted',
                    'Legendary',
                    'Mythical'
                ],
                selectedItemClasses: [],
                debounceMinValue: _.debounce(this.updateShopMinValue, 400),
                debounceMaxValue: _.debounce(this.updateShopMaxValue, 400)
            }
        },
        methods: {
            ...mapActions([
                'updateShop',
                'clearItemsToSell',
                'updateShopMinValue',
                'updateShopMaxValue',
                'updateShopItemBases',
                'updateShopItemClasses'
            ]),
            maybeUpdateShop() {
                let shopSlug = this.$route.params.shopSlug;
                if (this._shop.slug !== shopSlug) {
                    this.updateShop(this.$route);
                }
            },
            handleBuyClick(item) {
                alert(item.name);
            }
        },
        watch: {
            minValue: function (newAmount) {
                this.debounceMinValue(newAmount);
            },
            maxValue: function (newAmount) {
                this.debounceMaxValue(newAmount);
            },
            selectedItemBases: function (newItemBaseNames) {
                this.updateShopItemBases(newItemBaseNames);
            },
            selectedItemClasses: function (newItemClassNames) {
                this.updateShopItemClasses(newItemClassNames);
            },
        },
        computed: {
            ...mapGetters([
                '_shop',
                '_shopItems',
                '_mobileStorage',
                '_itemsToSell',
                '_shopFilters'
            ]),
            titleSizeClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'title';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'display-2'
                }
            },
            titleFontWeightClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'font-weight-medium';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'font-weight-bold'
                }
            },
            filteredMobileStorageItems() {
                return _.differenceBy(this._mobileStorage.items, this._itemsToSell, 'uuid');
            },
            sellGold() {
                if (this._itemsToSell.length > 0) {
                    return this._itemsToSell.reduce(function (total, itemToSell) {
                        return total + Math.floor(itemToSell.value * 0.6);
                    }, 0)
                }
                return 0;
            },
            itemBaseNames() {
                return this._shop.items.map(function (item) {
                    return item.itemType.itemBase.name;
                }).sort();
            }
        }
    }
</script>

<style scoped>

</style>

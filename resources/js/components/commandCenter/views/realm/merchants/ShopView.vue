<template>
    <TwoColumnWideLayout>
        <template v-slot:column-one>
            <v-sheet color="rgba(0, 20, 50, 0.4)" class="pa-2">
                <v-row no-gutters justify="center">
                    <span class="rh-op-85" :class="[titleSizeClass, titleFontWeightClass]">{{_shop.name}}</span>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12" lg="8" order="2" order-lg="1" class="pr-sm-2">
                        <ItemIterator :items="_shopItems" search-label="Search Shop">
                            <template v-slot:before-expand="props">
                                <div class="px-2">
                                    <v-btn
                                        x-small
                                        color="success"
                                        @click="handleBuyClick(props.item)"
                                        :disabled="buyItemDisabled(props.item)"
                                    >
                                        buy
                                    </v-btn>
                                </div>
                            </template>
                        </ItemIterator>
                    </v-col>
                    <v-col cols="12" lg="4" order="1" order-lg="2">
                        <v-row no-gutters class="pt-4">
                            <v-col cols="6" offset="3" lg="12" offset-lg="0" order-lg="5">
                                <v-row no-gutters align="center">
                                    <v-col cols="4" class="pt-4 px-4 pb-2">
                                        <GoldIcon></GoldIcon>
                                    </v-col>
                                    <v-col cols="8">
                                        <v-row no-gutters>
                                            <span class="headline font-weight-bold rh-op-80">{{_squad.gold.toLocaleString()}}</span>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="6" lg="12">
                                <v-select
                                    v-model="selectedItemBases"
                                    :items="itemBaseNames"
                                    :menu-props="{ maxHeight: '300' }"
                                    label="Item Bases"
                                    multiple
                                    outlined
                                    clearable
                                    class="mx-1"
                                ></v-select>
                            </v-col>
                            <v-col cols="6" lg="12">
                                <v-select
                                    v-model="selectedItemClasses"
                                    :items="itemClassNames"
                                    :menu-props="{ maxHeight: '300' }"
                                    label="Item Classes"
                                    multiple
                                    outlined
                                    clearable
                                    class="mx-1"
                                ></v-select>
                            </v-col>
                            <v-col cols="6" lg="12">
                                <v-text-field
                                    outlined
                                    clearable
                                    type="number"
                                    v-model="minValue"
                                    :label="'Min Value'"
                                    step="25"
                                    class="mx-1"
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="6" lg="12">
                                <v-text-field
                                    outlined
                                    clearable
                                    type="number"
                                    v-model="maxValue"
                                    :label="'Max Value'"
                                    step="25"
                                    class="mx-1"
                                >
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-sheet>
            <v-dialog
                v-model="buyItemDialog"
                max-width="400"
            >
                <v-card class="pa-2" color="#323f54">
                    <v-card-title>
                        <v-row no-gutters justify="center">
                            Buy Item for {{itemToBuy.shopPrice.toLocaleString()}} gold?
                        </v-row>
                    </v-card-title>
                    <ItemExpandPanel :item="itemToBuy"></ItemExpandPanel>
                    <v-card-actions justify="end">
                        <v-row no-gutters justify="end">
                            <v-btn
                                outlined
                                color="error"
                                @click="buyItemDialog = false"
                                class="mx-1"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="success"
                                class="mx-1"
                                @click="handleConfirmBuy"
                                :disabled="pending"
                            >
                                Buy
                            </v-btn>
                        </v-row>
                    </v-card-actions>
                </v-card>
            </v-dialog>
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
    import Item from "../../../../../models/Item";
    import ItemExpandPanel from "../../../global/ItemExpandPanel";
    import GoldIcon from "../../../../icons/GoldIcon";
    export default {
        name: "ShopView",
        components: {
            GoldIcon,
            ItemExpandPanel,
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
                pending: false,
                selectedItemBases: [],
                itemClassNames: [
                    'Generic',
                    'Enchanted',
                    'Legendary',
                    'Mythical'
                ],
                selectedItemClasses: [],
                itemToBuy: new Item({}),
                buyItemDialog: false,
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
                'updateShopItemClasses',
                'squadBuyItemFromShop'
            ]),
            maybeUpdateShop() {
                let shopSlug = this.$route.params.shopSlug;
                if (this._shop.slug !== shopSlug) {
                    this.updateShop(this.$route);
                }
            },
            handleBuyClick(item) {
                this.buyItemDialog = true;
                this.itemToBuy = item;
            },
            async handleConfirmBuy() {
                this.pending = true;
                await this.squadBuyItemFromShop({
                    route: this.$route,
                    item: this.itemToBuy
                });
                this.pending = false;
                this.buyItemDialog = false;
            },
            buyItemDisabled(item) {
                if (this.pending) {
                    return true;
                }
                return this._squad.gold < item.shopPrice;
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
                '_shopFilters',
                '_squad'
            ]),
            titleSizeClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'display-1';
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

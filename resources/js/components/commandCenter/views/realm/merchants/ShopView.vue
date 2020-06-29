<template>
    <TwoColumnWideLayout>
        <template v-slot:column-one>
            <v-sheet color="rgba(0, 20, 50, 0.4)" class="pa-2">
                <v-row no-gutters justify="center" class="py-md-2">
                    <span class="rh-op-85 text-center" :class="[titleSizeClass, titleFontWeightClass]">{{_shop.name}}</span>
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
                                    v-model="minPrice"
                                    :label="'Min Price'"
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
                                    v-model="maxPrice"
                                    :label="'Max Price'"
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
                            <div style="width: 48px" class="px-2 pt-2">
                                <GoldIcon></GoldIcon>
                            </div>
                            <span class="subtitle-1">
                                {{_shop.golfForItems(_itemsToSell).toLocaleString()}}
                            </span>
                            <v-spacer></v-spacer>
                            <v-btn
                                @click="clearItemsToSell"
                                color="error"
                                class="mx-1"
                                :disabled="_itemsToSell.length === 0 || pending"
                            >clear</v-btn>
                            <v-btn
                                color="primary"
                                class="mx-1"
                                @click="sellItemDialog = true"
                                :disabled="_itemsToSell.length === 0 || pending"
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
                                <RemoveItemToSellButton
                                    :item="props.item"
                                    :disabled="pending"
                                ></RemoveItemToSellButton>
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
            <v-dialog
                v-model="sellItemDialog"
                max-width="400"
            >
                <v-card class="pa-2" color="#363b45">
                    <v-card-title>
                        <v-row no-gutters justify="center">
                            {{sellItemMessage}}
                        </v-row>
                    </v-card-title>
                    <ItemIterator
                        :items="_itemsToSell"
                        :with-search="false"
                    >
                    </ItemIterator>
                    <v-card-actions justify="end">
                        <v-row no-gutters justify="end">
                            <v-btn
                                outlined
                                color="error"
                                @click="sellItemDialog = false"
                                class="mx-1"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="success"
                                class="mx-1"
                                @click="handleConfirmSell"
                                :disabled="pending"
                            >
                                Sell
                            </v-btn>
                        </v-row>
                    </v-card-actions>
                </v-card>
            </v-dialog>
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
                minPrice: null,
                maxPrice: null,
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
                sellItemDialog: false,
                debounceMinPrice: _.debounce(this.updateShopMinPrice, 400),
                debounceMaxPrice: _.debounce(this.updateShopMaxPrice, 400)
            }
        },
        methods: {
            ...mapActions([
                'updateShop',
                'clearItemsToSell',
                'updateShopMinPrice',
                'updateShopMaxPrice',
                'updateShopItemBases',
                'updateShopItemClasses',
                'squadBuyItemFromShop',
                'squadSellItemBundleToShop'
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
            async handleConfirmSell() {
                this.pending = true;
                await this.squadSellItemBundleToShop({
                    squad: this._squad,
                    shop: this._shop,
                    items: this._itemsToSell
                });
                this.sellItemDialog = false;
                // Needed to close dialog in time for items to clear
                await setTimeout(() => {
                    this.clearItemsToSell();
                    this.pending = false;
                }, 250);

            },
            buyItemDisabled(item) {
                if (this.pending) {
                    return true;
                }
                return this._squad.gold < item.shopPrice;
            }
        },
        watch: {
            minPrice: function (newAmount) {
                this.debounceMinPrice(newAmount);
            },
            maxPrice: function (newAmount) {
                this.debounceMaxPrice(newAmount);
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
            itemBaseNames() {
                return this._shop.items.map(function (item) {
                    return item.itemType.itemBase.name;
                }).sort();
            },
            sellItemText() {
                return this._itemsToSell.length === 1 ? 'item' : 'items';
            },
            sellItemMessage() {
                let message = 'Sell ';
                let count = this._itemsToSell.length;
                if (count === 1) {
                    message += 'item';
                } else {
                    message += count + ' items';
                }
                message += ' for ' + this._shop.golfForItems(this._itemsToSell).toLocaleString() + ' gold?';
                return message;
            }
        }
    }
</script>

<style scoped>

</style>
<template>
    <TwoColumnWideLayout>
        <template v-slot:column-one>
            <v-row no-gutters justify="center">
                <span class="rh-op-85" :class="[titleSizeClass, titleFontWeightClass]">{{_shop.name}}</span>
            </v-row>
            <v-row>
                <v-col cols="12" md="8" offset-md="2">
                    <ItemIterator :items="_shop.items" search-label="Search Shop">
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
        methods: {
            ...mapActions([
                'updateShop',
                'clearItemsToSell'
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
        computed: {
            ...mapGetters([
                '_shop',
                '_mobileStorage',
                '_itemsToSell'
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
            }
        }
    }
</script>

<style scoped>

</style>

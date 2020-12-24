import * as squadApi from '../../api/squadApi';
import Shop from "../../models/Shop";
import * as helpers from "../../helpers/vuexHelpers";
import Item from "../../models/Item";
import * as jsSearch from 'js-search';

export default {

    state: {
        shop: new Shop({}),
        shopLoaded: false,
        itemsToSell: [],
        shopSearch: '',
        shopFilters: {
            minPrice: {
                value: null,
                method: function (item, minPrice) {
                    return item.shopPrice >= minPrice;
                }
            },
            maxPrice: {
                value: null,
                method: function (item, maxPrice) {
                    return item.shopPrice <= maxPrice;
                }
            },
            itemBases: {
                value: [],
                method: function (item, itemBaseNames) {
                    let baseName = item.itemType.itemBase.name;
                    return itemBaseNames.includes(baseName);
                }
            },
            itemClasses: {
                value: [],
                method: function (item, itemClassNames) {
                    let className = item.itemClass.name;
                    return itemClassNames.includes(className);
                }
            }
        }
    },

    getters: {
        _shop(state) {
            return state.shop;
        },
        _shopLoaded(state) {
            return state.shopLoaded;
        },
        _itemsToSell(state) {
            return state.itemsToSell;
        },
        _filteredShopItems(state) {
            let items = state.shop.items;
            if (state.shopSearch) {
                let search = new jsSearch.Search('uuid');
                search.addIndex(['name']);
                search.addIndex(['itemType', 'name']);
                search.addIndex(['itemClass', 'name']);
                search.addIndex(['material', 'name']);
                search.addDocuments(items);
                items = search.search(state.shopSearch);
            }
            for (let key in state.shopFilters) {
                let filter = state.shopFilters[key];
                if ( ! (filter.value === null || filter.value.length === 0)) {
                    items = items.filter((item) => filter.method(item, filter.value));
                }
            }
            return items;
        }
    },
    mutations: {
        SET_SHOP(state, shop) {
            state.shop = shop;
        },
        SET_SHOP_LOADED(state) {
            state.shopLoaded = true;
        },
        ADD_ITEM_TO_SELL(state, item) {
            state.itemsToSell.push(item);
        },
        REMOVE_ITEM_TO_SELL(state, item) {
            state.itemsToSell = state.itemsToSell.filter((itemToSell) => itemToSell.uuid !== item.uuid);
        },
        CLEAR_ITEMS_TO_SELL(state) {
            state.itemsToSell = [];
        },
        SET_SHOP_SEARCH(state, shopSearch) {
            state.shopSearch = shopSearch
        },
        SET_SHOP_MIN_VALUE(state, minPrice) {
            let updateShopFilters = _.cloneDeep(state.shopFilters);
            updateShopFilters.minPrice.value = minPrice;
            state.shopFilters = updateShopFilters;
        },
        SET_SHOP_MAX_VALUE(state, maxPrice) {
            let updateShopFilters = _.cloneDeep(state.shopFilters);
            updateShopFilters.maxPrice.value = maxPrice;
            state.shopFilters = updateShopFilters;
        },
        SET_SHOP_ITEM_BASE_NAMES(state, itemBaseNames) {
            let updateShopFilters = _.cloneDeep(state.shopFilters);
            updateShopFilters.itemBases.value = itemBaseNames;
            state.shopFilters = updateShopFilters;
        },
        SET_SHOP_ITEM_CLASS_NAMES(state, itemClassNames) {
            let updateShopFilters = _.cloneDeep(state.shopFilters);
            updateShopFilters.itemClasses.value = itemClassNames;
            state.shopFilters = updateShopFilters;
        },
        REMOVE_ITEM_FROM_SHOP(state, item) {
            let updatedShop = _.cloneDeep(state.shop);
            updatedShop.items = updatedShop.items.filter((shopItem) => shopItem.uuid !== item.uuid);
            state.shop = updatedShop;
        }
    },

    actions: {
        async updateShop({commit}, route) {
            try {
                let response = await squadApi.getShop(route.params.squadSlug, route.params.shopSlug);
                let shop = new Shop(response.data);
                commit('SET_SHOP', shop);
                commit('SET_SHOP_LOADED')
            } catch (e) {
                console.warn("Failed to update shop");
            }
        },

        async squadBuyItemFromShop({state, commit, dispatch}, {route, item}) {
            try {
                let response = await squadApi.buyItemFromShop(route.params.squadSlug, route.params.shopSlug, item.uuid);
                let updatedItems = response.data.map(function (itemData) {
                    return new Item(itemData);
                });

                helpers.handleItemTransactions({state, commit, dispatch}, updatedItems);
                commit('REMOVE_ITEM_FROM_SHOP', item);
                commit('DECREASE_SQUAD_GOLD', item.shopPrice);

                dispatch('snackBarSuccess', {
                    text: item.name + ' purchased',
                    timeout: 2000
                });
            } catch (e) {
                helpers.handleResponseErrors(e, 'buy', dispatch);
            }
        },
        async squadSellItemBundleToShop({state, commit, dispatch}, {squad, shop, items}) {
            try {
                let itemUuids = items.map((item) => item.uuid);
                let response = await squadApi.sellItemBundleToShop(squad.slug, shop.slug, itemUuids);
                let updatedItems = response.data.map(function (itemData) {
                    return new Item(itemData);
                });

                let gold = shop.goldForItems(items);
                helpers.handleItemTransactions({state, commit, dispatch}, updatedItems);

                commit('INCREASE_SQUAD_GOLD', gold);
                dispatch('snackBarSuccess', {
                    text: items.length + ' items sold for ' + gold + ' gold',
                    timeout: 2000
                });

            } catch (e) {
                console.log(e);
                helpers.handleResponseErrors(e, 'sell', dispatch);
            }
        },
        addItemToSell({commit}, item) {
            commit('ADD_ITEM_TO_SELL', item);
        },
        removeItemToSell({commit}, item) {
            commit('REMOVE_ITEM_TO_SELL', item);
        },
        clearItemsToSell({commit}) {
            commit('CLEAR_ITEMS_TO_SELL');
        },
        updateShopSearch({commit}, shopSearch) {
            commit('SET_SHOP_SEARCH', shopSearch);
        },
        updateShopMinPrice({commit}, minPrice) {
            commit('SET_SHOP_MIN_VALUE', minPrice);
        },
        updateShopMaxPrice({commit}, maxPrice) {
            commit('SET_SHOP_MAX_VALUE', maxPrice);
        },
        updateShopItemBases({commit}, itemBaseNames) {
            commit('SET_SHOP_ITEM_BASE_NAMES', itemBaseNames);
        },
        updateShopItemClasses({commit}, itemClassNames) {
            commit('SET_SHOP_ITEM_CLASS_NAMES', itemClassNames);
        }
    }
};

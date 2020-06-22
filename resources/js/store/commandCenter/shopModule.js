import * as squadApi from '../../api/squadApi';
import Shop from "../../models/Shop";

export default {

    state: {
        shop: new Shop({}),
        itemsToSell: [],
        shopFilters: {
            minValue: {
                value: null,
                method: function (item, value) {
                    return item.value >= value;
                }
            },
            maxValue: {
                value: null,
                method: function (item, value) {
                    return item.value <= value;
                }
            },
        }
    },

    getters: {
        _shop(state) {
            return state.shop;
        },
        _itemsToSell(state) {
            return state.itemsToSell;
        },
        _shopItems(state) {
            let items = state.shop.items;
            for (let key in state.shopFilters) {
                let filter = state.shopFilters[key];
                if ( ! (filter.value === null || filter.value.length === 0)) {
                    items = items.filter((item) => filter.method(item, filter.value));
                }
            }
            return items;
        },
        _shopFilters(state) {
            return state.shopFilters;
        }
    },
    mutations: {
        SET_SHOP(state, shop) {
            state.shop = shop;
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
        SET_SHOP_MIN_VALUE(state, minValue) {
            let updateShopFilters = _.cloneDeep(state.shopFilters);
            updateShopFilters.minValue.value = minValue;
            state.shopFilters = updateShopFilters;
        },
        SET_SHOP_MAX_VALUE(state, maxValue) {
            let updateShopFilters = _.cloneDeep(state.shopFilters);
            updateShopFilters.maxValue.value = maxValue;
            state.shopFilters = updateShopFilters;
        }
    },

    actions: {
        async updateShop({commit}, route) {
            try {
                let response = await squadApi.getShop(route.params.squadSlug, route.params.shopSlug);
                let shop = new Shop(response.data);
                commit('SET_SHOP', shop)
            } catch (e) {
                console.warn("Failed to update shop");
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
        updateShopMinValue({commit}, minValue) {
            commit('SET_SHOP_MIN_VALUE', minValue);
        },
        updateShopMaxValue({commit}, maxValue) {
            commit('SET_SHOP_MAX_VALUE', maxValue);
        }
    }
};

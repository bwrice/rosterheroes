import * as squadApi from '../../api/squadApi';
import Shop from "../../models/Shop";

export default {

    state: {
        shop: new Shop({}),
        itemsToSell: []
    },

    getters: {
        _shop(state) {
            return state.shop;
        },
        _itemsToSell(state) {
            return state.itemsToSell;
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
        }
    }
};

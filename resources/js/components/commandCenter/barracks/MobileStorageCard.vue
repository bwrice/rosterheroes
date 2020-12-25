<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{title}}</span>
        </v-col>
        <v-col cols="12">
            <ItemsGroup
                :items="_mobileStorage.items"
                :loading="! _mobileStorageLoaded"
                :empty-message="emptyMessage"
                :search-label="searchLabel"
                :count="6"
                class="mb-2"
            >
                <template v-slot:before-show-icon="{item}">
                    <StashItemFab :item="item" class="mr-1"></StashItemFab>
                </template>
            </ItemsGroup>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import StashItemFab from "./StashItemFab";
    import ItemsGroup from "../global/ItemsGroup";

    export default {
        name: "MobileStorageCard",
        components: {ItemsGroup, StashItemFab},
        computed: {
            ...mapGetters([
                '_mobileStorage',
                '_mobileStorageLoaded',
                '_mobileStorageRankName',
            ]),
            title() {
                let title = this._mobileStorageRankName.toUpperCase();
                title += ' (' + this._mobileStorage.capacityUsed + '/' + this._mobileStorage.maxCapacity + ')';
                return title;
            },
            searchLabel() {
                return 'Search ' +  this._mobileStorageRankName;
            },
            emptyMessage() {
                return this._mobileStorageRankName + ' is empty';
            }
        }
    }
</script>

<style scoped>

</style>

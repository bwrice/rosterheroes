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
                    <StashItemButton
                        :item="item"
                        class="mr-1"
                    ></StashItemButton>
                </template>

                <template v-slot:after-focused-back="{item}">
                    <StashItemButton
                        :item="item"
                        :fab="false"
                        class="mr-1"
                    ></StashItemButton>
                </template>
            </ItemsGroup>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import StashItemButton from "./StashItemButton";
    import ItemsGroup from "../global/ItemsGroup";

    export default {
        name: "MobileStorageCard",
        components: {ItemsGroup, StashItemButton},
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

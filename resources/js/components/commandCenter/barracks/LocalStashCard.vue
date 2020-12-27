<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">STASH ({{_currentLocationProvince.name}})</span>
        </v-col>
        <v-col cols="12">
            <ItemsGroup
                :items="_localStash.items"
                :search-label="'Search Stash'"
                :empty-message="emptyMessage"
                :loading="! _localStashLoaded"
                :bus="bus"
                class="mb-2"
            >

                <template v-slot:before-show-icon="{item}">
                    <MobileStoreItemButton
                        :item="item"
                        class="mr-1"
                    >
                    </MobileStoreItemButton>
                </template>


                <template v-slot:after-focused-back="{item}">

                    <MobileStoreItemButton
                        :item="item"
                        class="mr-1"
                        :fab="false"
                        @stored="handleItemStored"
                    >
                    </MobileStoreItemButton>
                </template>
            </ItemsGroup>
        </v-col>
    </v-row>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters } from 'vuex'
    import MobileStoreItemButton from "./MobileStoreItemButton";
    import ItemsGroup from "../global/ItemsGroup";

    export default {
        name: "LocalStashCard",
        components: {ItemsGroup, MobileStoreItemButton},
        data() {
            return {
                bus: new Vue(),
            }
        },
        computed: {
            ...mapGetters([
                '_localStash',
                '_localStashLoaded',
                '_currentLocationProvince'
            ]),
            emptyMessage() {
                return 'Stash in ' + this._currentLocationProvince.name + ' is empty';
            }
        },
        methods: {
            handleItemStored({item}) {
                this.bus.$emit('clearFocusedItem', {item});
            }
        }
    }
</script>

<style scoped>

</style>

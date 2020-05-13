<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">STASH ({{_currentLocationProvince.name}})</span>
        </v-col>
        <v-col v-if="stashHasItems" cols="12">
            <ItemIterator
                :items="_localStash.items"
                :search-label="searchLabel"
            >
                <template v-slot:before-expand="props">
                    <div class="px-2">
                        <MobileStoreItemButton
                            :item="props.item"
                        >
                        </MobileStoreItemButton>
                    </div>
                </template>
            </ItemIterator>
        </v-col>
        <v-col v-else cols="12">
            <v-sheet color="rgba(255,255,255, 0.25)" class="my-2">
                <v-row no-gutters class="pa-2" justify="center" align="center">
                    <span class="subtitle-1 font-weight-light">Stash in {{_currentLocationProvince.name}} is empty</span>
                </v-row>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
    import { mapGetters } from 'vuex'
    import PaginationBlock from "../global/PaginationBlock";
    import ItemIterator from "../global/ItemIterator";
    import MobileStoreItemButton from "./MobileStoreItemButton";

    export default {
        name: "LocalStashCard",
        components: {MobileStoreItemButton, ItemIterator, PaginationBlock},

        computed: {
            ...mapGetters([
                '_localStash',
                '_currentLocationProvince'
            ]),
            stashHasItems() {
                return this._localStash.items.length > 0;
            },
            searchLabel() {
                return 'Search Stash';
            }
        }
    }
</script>

<style scoped>

</style>

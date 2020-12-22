<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{title}}</span>
        </v-col>
        <v-col cols="12">
            <ItemsGroup :items="_mobileStorage.items">
                <template v-slot:before-show-icon="{item}">
                    <StashItemButton :item="item" class="mr-1"></StashItemButton>
                </template>
            </ItemsGroup>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import StashItemButton from "./StashItemButton";
    import ItemsGroup from "./ItemsGroup";

    export default {
        name: "MobileStorageCard",
        components: {ItemsGroup, StashItemButton},
        computed: {
            ...mapGetters([
                '_mobileStorage',
                '_mobileStorageRankName',
            ]),
            title() {
                let title = this._mobileStorageRankName.toUpperCase();
                title += ' (' + this._mobileStorage.capacityUsed + '/' + this._mobileStorage.maxCapacity + ')';
                return title;
            }
        }
    }
</script>

<style scoped>

</style>

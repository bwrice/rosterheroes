<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{title}}</span>
        </v-col>
        <v-col cols="12">
            <ItemIterator
                :items="_mobileStorage.items"
            >
                <template v-slot:before-expand="props">
                    <div class="px-2">
                        <StashItemButton
                            :item="props.item"
                        >
                        </StashItemButton>
                    </div>
                </template>
            </ItemIterator>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ItemIterator from "../global/ItemIterator";
    import StashItemButton from "./StashItemButton";

    export default {
        name: "MobileStorageCard",
        components: {StashItemButton, ItemIterator},
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

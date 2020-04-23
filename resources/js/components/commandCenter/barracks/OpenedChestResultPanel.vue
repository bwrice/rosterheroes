<template>
    <v-row no-gutters>
        <v-col cols="12" v-if="openedChestResult.itemsMovedToMobileStorage.length">
            <v-row no-gutters>
                <span class="font-weight-light"></span>
            </v-row>
            <v-row no-gutters>
                <v-col
                    cols="12"
                    v-for="(item, uuid) in openedChestResult.itemsMovedToMobileStorage"
                    :key="uuid"
                >
                    <ItemExpandPanel :item="item"></ItemExpandPanel>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import OpenedChestResult from "../../../models/OpenedChestResult";
    import ItemExpandPanel from "../global/ItemExpandPanel";

    export default {
        name: "OpenedChestResultPanel",
        components: {ItemExpandPanel},
        props: {
            openedChestResult: {
                type: OpenedChestResult,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_mobileStorageRankName',
            ]),
        }
    }
</script>

<style scoped>

</style>

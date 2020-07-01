<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-sheet color="rgba(0,0,0, 0.5)" class="ma-1 pa-1 rounded-sm">
                <span class="subtitle-1 font-weight-light mx-2">Gold: {{openedChestResult.gold.toLocaleString()}}</span>
                <span class="subtitle-1 font-weight-light mx-2">Items: {{openedChestResult.items.length}}</span>
            </v-sheet>
        </v-col>
        <template v-for="itemGroup in itemGroups">
            <v-col cols="12" v-if="itemGroup.items.length">
                <v-sheet color="rgba(0,0,0, 0.5)" class="ma-1 pa-1 rounded">
                    <v-row no-gutters>
                        <span class="font-weight-light mx-2">Items moved to {{itemGroup.description}}</span>
                    </v-row>
                    <v-row no-gutters>
                        <v-col
                            cols="12"
                            v-for="(item, uuid) in itemGroup.items"
                            :key="uuid"
                        >
                            <ItemExpandPanel :item="item"></ItemExpandPanel>
                        </v-col>
                    </v-row>
                </v-sheet>
            </v-col>
        </template>
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
                '_currentLocationProvince'
            ]),
            itemGroups() {
                return [
                    {
                        description: this._mobileStorageRankName,
                        items: this.openedChestResult.itemsMovedToMobileStorage
                    },
                    {
                        description: 'Residence (' + this._currentLocationProvince.name + ')',
                        items: this.openedChestResult.itemsMovedToResidence
                    },
                    {
                        description: 'Stash (' + this._currentLocationProvince.name + ')',
                        items: this.openedChestResult.itemsMovedToStash
                    }
                ];
            }
        }
    }
</script>

<style scoped>

</style>

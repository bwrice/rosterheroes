<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">TREASURE CHESTS</span>
        </v-col>
        <v-col cols="12">
            <PaginationBlock v-if="chestsAvailable"
                :items="_unopenedChests"
                :loading="_loadingUnopenedChests"
            >
                <template v-slot:default="slotProps">
                    <UnopenedChestPanel
                        :unopened-chest="slotProps.item"
                        @openChestClicked="openChest"
                    >
                    </UnopenedChestPanel>
                </template>
            </PaginationBlock>
            <v-sheet color="rgba(255,255,255, 0.25)" v-else>
                <v-row no-gutters class="pa-2" justify="center" align="center">
                    <span class="rh-op-70 subtitle-1">Add quests and side-quests to your campaign to earn chests</span>
                </v-row>
            </v-sheet>
        </v-col>
        <v-dialog
            v-model="chestDialog"
            max-width="600"
        >
            <v-sheet>
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row>
                            <span class="subtitle-1 font-weight-thin">Opening Chest...</span>
                        </v-row>
                    </v-col>
                </v-row>
            </v-sheet>

        </v-dialog>
    </v-row>
</template>

<script>

    import {mapGetters} from 'vuex';

    import PaginationBlock from "../global/PaginationBlock";
    import UnopenedChestPanel from "./UnopenedChestPanel";
    export default {
        name: "Treasury",
        components: {UnopenedChestPanel, PaginationBlock},
        computed: {
            ...mapGetters([
                '_unopenedChests',
                '_loadingUnopenedChests'
            ]),
            chestsAvailable() {
                if (this._loadingUnopenedChests) {
                    return true;
                }
                return this._unopenedChests.length > 0;
            }
        },
        data() {
            return {
                chestDialog: false
            }
        },
        methods: {
            openChest(unopenedChest) {
                this.chestDialog = true;
            }
        }
    }
</script>

<style scoped>

</style>

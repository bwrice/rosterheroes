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
                        @openChestClicked="handleOpenChestClicked"
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
            max-width="500"
        >
            <v-sheet
                color="#526e5a"
            >
                <v-row no-gutters>
                    <v-col cols="12">
                        <v-row no-gutters justify="center" class="px-2 pt-1">
                            <span class="title font-weight-bolder">{{dialogTitle}}</span>
                        </v-row>
                        <v-row
                            no-gutters
                            v-if="openingChestPending"
                            :justify="'center'"
                            class="py-6">
                            <v-progress-circular indeterminate size="36"></v-progress-circular>
                        </v-row>
                        <OpenedChestResultPanel
                            v-if="! openingChestPending && _lastOpenedChestResult"
                            :opened-chest-result="_lastOpenedChestResult"
                        >
                        </OpenedChestResultPanel>
                        <v-row
                            v-if="! openingChestPending"
                            no-gutters
                            justify="end"
                            align="center"
                            class="pa-2">
                            <v-btn
                                color="primary"
                                @click="chestDialog = false"
                                class="mx-1"
                            >
                                Close
                            </v-btn>
                        </v-row>
                    </v-col>
                </v-row>
            </v-sheet>

        </v-dialog>
    </v-row>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import PaginationBlock from "../global/PaginationBlock";
    import UnopenedChestPanel from "./UnopenedChestPanel";
    import OpenedChestResultPanel from "./OpenedChestResultPanel";

    export default {
        name: "Treasury",
        components: {OpenedChestResultPanel, UnopenedChestPanel, PaginationBlock},
        computed: {
            ...mapGetters([
                '_unopenedChests',
                '_loadingUnopenedChests',
                '_lastOpenedChestResult'
            ]),
            chestsAvailable() {
                if (this._loadingUnopenedChests) {
                    return true;
                }
                return this._unopenedChests.length > 0;
            },
            dialogTitle() {
                if (this.openingChestPending) {
                    return 'Opening Chest...';
                }
                return "Here\'s what you found!";
            }
        },
        data() {
            return {
                chestDialog: false,
                openingChestPending: false
            }
        },
        methods: {
            ...mapActions([
                'openChest',
            ]),
            async handleOpenChestClicked(unopenedChest) {
                this.openingChestPending = true;
                this.chestDialog = true;
                await this.openChest(unopenedChest);
                this.openingChestPending = false;
            },
        }
    }
</script>

<style scoped>

</style>

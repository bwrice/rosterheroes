<template>
    <v-row no-gutters>
        <v-col cols="12" class="pt-3">
            <span class="title font-weight-thin">GEAR</span>
        </v-col>
        <v-col cols="12">
            <v-row no-gutters>
                <v-col cols="12">
                    <v-sheet class="py-5 rounded"
                             style="background-image: linear-gradient(to bottom right, #524c59, #7c7287 , #524c59)">
                        <HeroGearSVG
                            :hero="hero"
                            @heroSlotClicked="handleHeroSlotClicked"
                            :interactive="true"
                        ></HeroGearSVG>
                    </v-sheet>
                </v-col>
            </v-row>
            <v-row justify="center" no-gutters>
                <v-col cols="12">
                    <ItemsGroup
                        :items="_mobileStorage.items"
                        :loading="! _mobileStorageLoaded"
                        :empty-message="emptyMessage"
                        :search-label="searchLabel"
                        class="mt-1 mb-2"
                    >
                        <template v-slot:before-show-icon="{item}">
                            <EquipFromMobileStorageButton
                                :hero="hero"
                                :item="item"
                                class="mr-1"
                            >
                            </EquipFromMobileStorageButton>
                        </template>

                        <template v-slot:after-focused-back="{item}">

                            <EquipFromMobileStorageButton
                                :hero="hero"
                                :item="item"
                                :fab="false"
                            >
                            </EquipFromMobileStorageButton>
                        </template>
                    </ItemsGroup>
                </v-col>
            </v-row>
        </v-col>
        <v-dialog
            v-model="slotDialog"
            max-width="400"
        >
            <v-card class="pa-2" color="#323f54">
                <v-card-title class="pb-0">
                    <v-row no-gutters class="px-2">
                        <v-col cols="10">
                            <span>{{gearSlot.type}}</span>
                        </v-col>
                        <v-col cols="2">
                            <v-row no-gutters justify="end">
                                <v-icon @click="slotDialog = false">close</v-icon>
                            </v-row>
                        </v-col>
                        <v-col cols="12" v-if="gearSlotCaption">
                            <span class="caption">{{gearSlotCaption}}</span>
                        </v-col>
                    </v-row>
                </v-card-title>
                <template v-if="gearSlot.item">
                    <v-row no-gutters justify="center">
                        <UnequipItemButton
                            :item="gearSlot.item"
                            :hero="hero"
                            class="my-2"
                        >
                        </UnequipItemButton>
                    </v-row>
                    <ItemCard
                        :item="gearSlot.item"
                        style="max-height: 400px; overflow-y: scroll; overflow-x: hidden"
                    ></ItemCard>
                </template>
                <template v-else>
                    <div class="d-flex justify-center align-center flex-column ma-4"
                         style="height: 80px; border: dashed 1px #6f808c; border-radius: 16px">
                        <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">
                           {{gearSlot.type + ' is empty'}}
                        </span>
                    </div>
                </template>
                <v-divider class="my-4"></v-divider>
                <v-row no-gutters>
                    <v-col cols="12">
                        <ItemsGroup
                            :items="itemsForSlot"
                            :loading="! _mobileStorageLoaded"
                            :empty-message="emptyMessageForSlot"
                            :search-label="searchLabel"
                            class="mt-1 mb-2"
                        >
                            <template v-slot:before-show-icon="{item}">
                                <EquipFromMobileStorageButton
                                    :hero="hero"
                                    :item="item"
                                    class="mr-1"
                                >
                                </EquipFromMobileStorageButton>
                            </template>

                            <template v-slot:after-focused-back="{item}">

                                <EquipFromMobileStorageButton
                                    :hero="hero"
                                    :item="item"
                                    :fab="false"
                                >
                                </EquipFromMobileStorageButton>
                            </template>
                        </ItemsGroup>
                    </v-col>
                </v-row>
                <v-card-actions>
                    <v-btn
                        href="#hero-gear-card"
                        @click="slotDialog = false"
                        block
                    >Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";

    import HeroGearSVG from "./HeroGearSVG";
    import UnequipItemButton from "./UnequipItemButton";
    import EquipFromMobileStorageButton from "./EquipFromMobileStorageButton";
    import GearSlot from "../../../../models/GearSlot";
    import ItemExpandPanel from "../../global/ItemExpandPanel";
    import ItemsGroup from "../../global/ItemsGroup";
    import ItemCard from "../../global/ItemCard";

    export default {
        name: "HeroGearCard",
        components: {
            ItemCard,
            ItemsGroup,
            ItemExpandPanel,
            EquipFromMobileStorageButton,
            UnequipItemButton,
            HeroGearSVG
        },
        mixins: [
            barracksHeroMixin
        ],
        data() {
            return {
                slotDialog: false,
                focusedSlotType: null
            }
        },
        methods: {
            handleHeroSlotClicked(slotType) {
                this.slotDialog = true;
                this.focusedSlotType = slotType;
            }
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_mobileStorage',
                '_mobileStorageRankName',
                '_mobileStorageLoaded',
                '_focusedHero'
            ]),
            hero() {
                return this._focusedHero(this.$route);
            },
            gearSlot() {
                if (! this.focusedSlotType) {
                    return new GearSlot({});
                }
                let focusedGearSlot = this.hero.getGearSlotByType(this.focusedSlotType);
                return focusedGearSlot ? focusedGearSlot : new GearSlot({});
            },
            itemsForSlot() {
                if (! this.focusedSlotType) {
                    return [];
                }

                let type = this.gearSlot.type;

                return this._mobileStorage.items.filter(function (item) {
                    let slotTypeNames = item.itemType.itemBase.slotTypeNames;
                    return slotTypeNames.includes(type);
                });
            },
            gearSlotCaption() {
                if (this.gearSlot.type === 'Off-Arm') {
                    return 'Equipped items will prioritize Primary Arm';
                }
                if (this.gearSlot.type === 'Off-Wrist') {
                    return 'Equipped items will prioritize Primary Wrist';
                }
                if (this.gearSlot.type === 'Ring Two') {
                    return 'Equipped items will prioritize Ring One';
                }
                return null;
            },
            searchLabel() {
                return 'Search ' +  this._mobileStorageRankName;
            },
            emptyMessage() {
                return this._mobileStorageRankName + ' is empty';
            },
            emptyMessageForSlot() {
                return this._mobileStorageRankName + ' has no items available for ' + this.gearSlot.type;
            }
        }
    }
</script>

<style scoped>

</style>

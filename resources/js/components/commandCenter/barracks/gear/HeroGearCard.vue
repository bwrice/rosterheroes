<template>
    <v-row no-gutters>
        <v-col cols="12" class="pt-3">
            <span class="title font-weight-thin">GEAR</span>
        </v-col>
        <v-col cols="12" v-if="focusedSlotType">
            <v-card color="#524c59">
                <v-card-title class="pb-0">
                    <v-row justify="center" class="px-2">
                        <v-row no-gutters class="flex-column">
                            <span>{{gearSlot.type}}</span>
                            <span v-if="gearSlotCaption" class="caption">{{gearSlotCaption}}</span>
                        </v-row>
                        <div class="flex-grow-1"></div>
                        <v-icon class="align-self-baseline" @click="focusedSlotType = null">close</v-icon>
                    </v-row>
                </v-card-title>
                <v-card-text class="px-2 pb-0">
                    <v-row no-gutters align="center" justify="center">
                        <template v-if="gearSlot.item">
                            <v-col cols="12" class="pt-2">
                                <v-row no-gutters align="center" justify="center">
                                    <UnequipItemButton
                                        :item="gearSlot.item"
                                        :hero="hero"
                                    >
                                    </UnequipItemButton>
                                    <v-col cols="12">
                                        <v-sheet color="#456d87" class="my-2">
                                            <ItemCard :item="gearSlot.item"></ItemCard>
                                        </v-sheet>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </template>
                        <template v-else>
                            <span class="subtitle-1 font-weight-light">(empty)</span>
                        </template>
                    </v-row>
                    <v-row justify="center" no-gutters>
                        <v-col cols="12">
                            <ItemIterator
                                :items="itemsForSlot"
                                :items-per-page="6"
                                :search-label="'Search Wagon'"
                                :key="'uuid'"
                                :item-name-truncate-extra="4"
                            >
                                <template v-slot:before-expand="props">
                                    <div class="px-2">
                                        <EquipFromMobileStorageButton
                                            :hero="hero"
                                            :item="props.item"
                                        >
                                        </EquipFromMobileStorageButton>
                                    </div>
                                </template>
                            </ItemIterator>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        href="#hero-gear-card"
                        @click="focusedSlotType = null"
                        block
                    >Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
        <v-col cols="12" v-else>
            <v-row no-gutters>
                <v-col cols="12">
                    <v-sheet class="py-5"
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
                    <ItemIterator
                        :items="_mobileStorage.items"
                        :items-per-page="6"
                        :search-label="'Search Wagon'"
                        :key="'uuid'"
                        :item-name-truncate-extra="4"
                    >
                        <template v-slot:before-expand="props">
                            <div class="px-2">
                                <EquipFromMobileStorageButton
                                    :hero="hero"
                                    :item="props.item"
                                >
                                </EquipFromMobileStorageButton>
                            </div>
                        </template>
                    </ItemIterator>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";

    import HeroGearSVG from "./HeroGearSVG";
    import ItemCard from "../../global/ItemCard";
    import UnequipItemButton from "./UnequipItemButton";
    import ItemIterator from "../../global/ItemIterator";
    import EquipFromMobileStorageButton from "./EquipFromMobileStorageButton";
    import GearSlot from "../../../../models/GearSlot";

    export default {
        name: "HeroGearCard",
        components: {
            EquipFromMobileStorageButton,
            ItemIterator,
            UnequipItemButton,
            ItemCard,
            HeroGearSVG
        },
        mixins: [
            barracksHeroMixin
        ],
        data() {
            return {
                focusedSlotType: null
            }
        },
        methods: {
            handleHeroSlotClicked(slotType) {
                this.focusedSlotType = slotType;
            },
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_mobileStorage',
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
            }
        }
    }
</script>

<style scoped>

</style>

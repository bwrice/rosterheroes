<template>
    <div id="hero-gear-card">
        <v-card v-if="focusedSlotType">
            <v-card-title class="pb-0">
                <v-row align="center" justify="center" class="px-2">
                    <span>{{gearSlot.type}}</span>
                    <div class="flex-grow-1"></div>
                    <v-icon @click="focusedSlotType = null">close</v-icon>
                </v-row>
            </v-card-title>
            <v-card-text class="px-2">
                <v-row align="center" justify="center">
                    <template v-if="gearSlot.item">
                        <v-col cols="12">
                            <v-row no-gutters align="center" justify="center">
                                <EmptyHeroSlotButton
                                    :heroSlot="gearSlot"
                                    :hero="hero"
                                >
                                </EmptyHeroSlotButton>
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
            </v-card-text>
            <v-card-actions>
                <v-btn href="#hero-gear-card" @click="focusedSlotType = null" block>Close</v-btn>
            </v-card-actions>
        </v-card>
        <v-card v-else>
            <v-card-text class="pa-2">
                <v-sheet class="py-5"
                         style="background-image: linear-gradient(to bottom right, #524c59, #7c7287 , #524c59)">
                    <HeroGearSVG
                        :hero="hero"
                        @heroSlotClicked="handleHeroSlotClicked"
                    ></HeroGearSVG>
                </v-sheet>
                <v-row justify="center" no-gutters>
                    <v-col cols="12">
                        <!-- key on FilledSlotIterator prevents pagination persisting -->
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
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";

    import HeroGearSVG from "./HeroGearSVG";
    import ItemCard from "../../global/ItemCard";
    import EmptyHeroSlotButton from "./EmptyHeroSlotButton";
    import ItemIterator from "../../global/ItemIterator";
    import EquipFromMobileStorageButton from "./EquipFromMobileStorageButton";
    import GearSlot from "../../../../models/GearSlot";

    export default {
        name: "HeroGearCard",
        components: {
            EquipFromMobileStorageButton,
            ItemIterator,
            EmptyHeroSlotButton,
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
            mobileStorageSlots() {
                let focusedSlot = this.gearSlot;
                return this._mobileStorage.filledSlots.filter(function (filledSlot) {
                    let itemBaseSlotTypeNames = filledSlot.item.itemType.itemBase.slotTypeNames;
                    return itemBaseSlotTypeNames.find(function (slotTypeName) {
                        return slotTypeName === focusedSlot.slotType.name;
                    });
                })
            }
        }
    }
</script>

<style scoped>

</style>

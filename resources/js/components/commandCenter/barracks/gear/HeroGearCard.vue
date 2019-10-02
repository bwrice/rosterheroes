<template>
    <v-card>
        <v-sheet class="py-5" style="background-image: linear-gradient(to bottom right, #524c59, #7c7287 , #524c59)">
            <HeroGearSVG
                v-if="barracksHeroFromRoute"
                :hero="barracksHeroFromRoute"
                @heroSlotClicked="handleHeroSlotClicked"
            ></HeroGearSVG>
        </v-sheet>
        <v-dialog
            v-model="heroGearDialog"
            max-width="600">
            <v-card>
                <v-card-title class="pb-0">
                    <v-row align="center" justify="center">
                        <span>{{focusedHeroSlot.slotType.displayName}}</span>
                        <div class="flex-grow-1"></div>
                        <v-icon @click="heroGearDialog = false">close</v-icon>
                    </v-row>
                </v-card-title>
                <v-card-text class="px-2">
                    <v-row align="center" justify="center">
                        <template v-if="focusedHeroSlot.item">
                            <v-col cols="12">
                                <v-row align="center" justify="center" class="py-2">
                                    <EmptyHeroSlotButton :heroSlot="focusedHeroSlot"
                                                         :hero="barracksHeroFromRoute"></EmptyHeroSlotButton>
                                </v-row>
                                <ItemCard :item="focusedHeroSlot.item"></ItemCard>
                            </v-col>
                        </template>
                        <template v-else>
                            <span class="subtitle-1 font-weight-light">(empty)</span>
                        </template>
                    </v-row>
                    <v-row justify="center" no-gutters>
                        <v-col cols="12">
                            <!-- key on FilledSlotIterator prevents pagination persisting -->
                            <FilledSlotIterator
                                :filled-slots="mobileStorageSlots"
                                :items-per-page="4"
                                :search-label="'Search Wagon'"
                                :key="this.focusedHeroSlot.uuid"
                            >
                                <template v-slot:before-expand="props">
                                    <div class="px-2">
                                        <FillSlotFromWagonButton
                                            :hero="barracksHeroFromRoute"
                                            :hero-slot="focusedHeroSlot"
                                            :item="props.item"
                                        >
                                        </FillSlotFromWagonButton>
                                    </div>
                                </template>
                            </FilledSlotIterator>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="heroGearDialog = null" block>Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
    import Slot from "../../../../models/Slot";

    import {mapGetters} from 'vuex';
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";

    import HeroGearSVG from "./HeroGearSVG";
    import ItemCard from "../../global/ItemCard";
    import EmptyHeroSlotButton from "./EmptyHeroSlotButton";
    import FilledSlotIterator from "../../global/FilledSlotIterator";
    import FillSlotFromWagonButton from "./FillSlotFromWagonButton";

    export default {
        name: "HeroGearCard",
        components: {
            FillSlotFromWagonButton,
            FilledSlotIterator,
            EmptyHeroSlotButton,
            ItemCard,
            HeroGearSVG
        },
        mixins: [
            barracksHeroMixin
        ],
        data() {
            return {
                heroGearDialog: false,
                focusedSlotUuid: null
            }
        },
        methods: {
            handleHeroSlotClicked(slotUuid) {
                this.focusedSlotUuid = slotUuid;
                this.heroGearDialog = true;
            }
        },
        computed: {
            ...mapGetters([
                '_barracksHeroes',
                '_mobileStorage'
            ]),
            focusedHeroSlot() {
                if (! this.focusedSlotUuid) {
                    return new Slot({});
                }
                let focusedSlot = this.barracksHeroFromRoute.getSlot(this.focusedSlotUuid);
                return focusedSlot ? focusedSlot : new Slot({});
            },
            mobileStorageSlots() {
                let focusedSlot = this.focusedHeroSlot;
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

<template>
    <div id="hero-gear-card">
        <v-card v-if="focusedSlotUuid">
            <v-card-title class="pb-0">
                <v-row align="center" justify="center" class="px-2">
                    <span>{{focusedHeroSlot.slotType.displayName}}</span>
                    <div class="flex-grow-1"></div>
                    <v-icon @click="focusedSlotUuid = null">close</v-icon>
                </v-row>
            </v-card-title>
            <v-card-text class="px-2">
                <v-row align="center" justify="center">
                    <template v-if="focusedHeroSlot.item">
                        <v-col cols="12">
                            <v-row no-gutters align="center" justify="center">
                                <EmptyHeroSlotButton
                                    :heroSlot="focusedHeroSlot"
                                    :hero="barracksHeroFromRoute"
                                >
                                </EmptyHeroSlotButton>
                                <v-col cols="12">
                                    <v-sheet color="#456d87" class="my-2">
                                        <ItemCard :item="focusedHeroSlot.item"></ItemCard>
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
                        <!-- key on FilledSlotIterator prevents pagination persisting -->
                        <FilledSlotIterator
                            :filled-slots="mobileStorageSlots"
                            :items-per-page="6"
                            :search-label="'Search Wagon'"
                            :key="this.focusedHeroSlot.uuid"
                            :item-name-truncate-extra="4"
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
                <v-btn href="#hero-gear-card" @click="focusedSlotUuid = null" block>Close</v-btn>
            </v-card-actions>
        </v-card>
        <v-card v-else>
            <v-sheet class="py-5"
                     style="background-image: linear-gradient(to bottom right, #524c59, #7c7287 , #524c59)">
                <HeroGearSVG
                    :hero="focusedHero"
                    @heroSlotClicked="handleHeroSlotClicked"
                ></HeroGearSVG>
            </v-sheet>
        </v-card>
    </div>
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
                focusedSlotUuid: null
            }
        },
        methods: {
            handleHeroSlotClicked(slotUuid) {
                this.focusedSlotUuid = slotUuid;
            },
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_mobileStorage',
                '_focusedHero'
            ]),
            focusedHero() {
                return this._focusedHero(this.$route);
            },
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

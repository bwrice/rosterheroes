<template>
    <v-card>
        <v-sheet class="py-5" style="background-image: linear-gradient(to bottom right, #524c59, #7c7287 , #524c59 )">
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
                <v-card-title>{{focusedHeroSlot.slotType.displayName}}</v-card-title>
                <v-card-text class="pa-2">
                    <template v-if="focusedHeroSlot.item">
                        <EmptyHeroSlotButton :heroSlot="focusedHeroSlot" :hero="barracksHeroFromRoute"></EmptyHeroSlotButton>
                        <ItemPanel :item="focusedHeroSlot.item"></ItemPanel>
                    </template>
                    <template v-else>
                        <h3>(EMPTY)</h3>
                    </template>
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
    import ItemPanel from "../../global/ItemCard";
    import EmptyHeroSlotButton from "./EmptyHeroSlotButton";

    export default {
        name: "HeroGearCard",
        components: {EmptyHeroSlotButton, ItemPanel, HeroGearSVG},
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
            ]),
            focusedHeroSlot() {
                if (! this.focusedSlotUuid) {
                    return new Slot({});
                }
                let focusedSlot = this.barracksHeroFromRoute.getSlot(this.focusedSlotUuid);
                return focusedSlot ? focusedSlot : new Slot({});
            }
        }
    }
</script>

<style scoped>

</style>

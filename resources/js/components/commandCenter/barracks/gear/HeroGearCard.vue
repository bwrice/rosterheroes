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
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <v-card>
                            <v-card-title>{{}}</v-card-title>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-dialog>
    </v-card>
</template>

<script>
    import Slot from "../../../../models/Slot";

    import {mapGetters} from 'vuex';
    import {barracksHeroMixin} from "../../../../mixins/barracksHeroMixin";

    import HeroGearSVG from "./HeroGearSVG";

    export default {
        name: "HeroGearCard",
        components: {HeroGearSVG},
        mixins: [
            barracksHeroMixin
        ],
        data() {
            return {
                heroGearDialog: false,
                focusedHeroSlot: new Slot({})
            }
        },
        methods: {
            handleHeroSlotClicked({heroSlot}) {
                console.log("CLICKED");
            }
        },
        computed: {
            ...mapGetters([
                '_barracksHeroes'
            ]),
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-btn
        small
        @click="emptySlot"
        :disabled="pending"
        color="#c7830e"
    >
        Un-Equip {{itemName}}
    </v-btn>
</template>

<script>
    import Slot from "../../../../models/Slot";
    import BarracksHero from "../../../../models/BarracksHero";
    import {mapActions} from 'vuex';

    export default {
        name: "EmptyHeroSlotButton",
        props: {
            heroSlot: {
                type: Slot,
                required: true
            },
            hero: {
                type: BarracksHero,
                required: true
            }
        },

        data() {
            return {
                pending: false
            }
        },

        methods: {
            ...mapActions([
                'emptyHeroSlot'
            ]),
            async emptySlot() {
                this.pending = true;
                await this.emptyHeroSlot({
                    heroSlug: this.hero.slug,
                    slotUuid: this.heroSlot.uuid
                });
                this.pending = false;
            }
        },
        computed: {
            itemName() {
                if (this.heroSlot.item) {
                    let windowWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
                    let maxLength = Math.floor(windowWidth/18);
                    maxLength = Math.min(maxLength, 26);
                    return _.truncate(this.heroSlot.item.name, {
                        length: maxLength
                    });
                }
                return '';
            }
        }
    }
</script>

<style scoped>

</style>

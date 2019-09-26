<template>
    <v-btn @click="emptySlot" :disabled="pending">Un-Equip Item</v-btn>
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
        }
    }
</script>

<style scoped>

</style>

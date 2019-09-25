<template>
    <v-btn @click="emptySlot">Un-Equip Item</v-btn>
</template>

<script>
    import Slot from "../../../../models/Slot";
    import BarracksHero from "../../../../models/BarracksHero";

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
            async emptySlot() {
                this.pending = true;
            }
        }
    }
</script>

<style scoped>

</style>

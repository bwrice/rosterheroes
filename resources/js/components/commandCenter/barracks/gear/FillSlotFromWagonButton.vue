<template>
    <v-btn
        fab
        x-small
        color="success"
        @click="equip"
        :disabled="pending"
    >
        <v-icon>unarchive</v-icon>
    </v-btn>
</template>

<script>
    import Hero from "../../../../models/Hero";
    import Slot from "../../../../models/Slot";
    import Item from "../../../../models/Item";

    import {mapActions} from 'vuex';

    export default {
        name: "FillSlotFromWagonButton",
        props: {
            hero: {
                type: Hero,
                required: true
            },
            heroSlot: {
                type: Slot,
                required: true
            },
            item: {
                type: Item,
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
                'equipHeroSlotFromWagon'
            ]),
            async equip() {
                this.pending = true;
                await this.equipHeroSlotFromWagon({
                    heroSlug: this.hero.slug,
                    slotUuid: this.heroSlot.uuid,
                    itemUuid: this.item.uuid
                });
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>

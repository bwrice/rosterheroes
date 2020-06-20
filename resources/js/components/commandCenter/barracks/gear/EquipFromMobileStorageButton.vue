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
    import Item from "../../../../models/Item";

    import {mapActions} from 'vuex';

    export default {
        name: "EquipFromMobileStorageButton",
        props: {
            hero: {
                type: Hero,
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
                'equipHeroFromMobileStorage'
            ]),
            async equip() {
                this.pending = true;
                await this.equipHeroFromMobileStorage({
                    heroSlug: this.hero.slug,
                    item: this.item
                });
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>

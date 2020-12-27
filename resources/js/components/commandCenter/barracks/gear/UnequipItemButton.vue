<template>
    <v-btn
        @click="emptySlot"
        :disabled="pending"
        color="primary"
    >
        Un-Equip {{itemName}}
    </v-btn>
</template>

<script>
    import Hero from "../../../../models/Hero";
    import {mapActions} from 'vuex';
    import Item from "../../../../models/Item";

    export default {
        name: "UnequipItemButton",
        props: {
            item: {
                type: Item,
                required: true
            },
            hero: {
                type: Hero,
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
                'unequipItem'
            ]),
            async emptySlot() {
                this.pending = true;
                await this.unequipItem({
                    heroSlug: this.hero.slug,
                    item: this.item
                });
                this.pending = false;
            }
        },
        computed: {
            itemName() {
                if (this.item) {
                    let windowWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
                    let maxLength = Math.floor(windowWidth/18);
                    maxLength = Math.min(maxLength, 26);
                    return _.truncate(this.item.name, {
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

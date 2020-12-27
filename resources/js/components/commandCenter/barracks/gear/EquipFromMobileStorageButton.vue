<template>
    <v-btn
        :fab="fab"
        :x-small="fab"
        color="success"
        @click="equip"
        :disabled="pending"
    >
        <v-icon :left="! fab">unarchive</v-icon>
        {{ fab ? '' : 'equip'}}
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
            },
            fab: {
                type: Boolean,
                default: true
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
                this.$emit('equipped', {item: this.item, hero: this.hero});
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-btn
        :x-small="fab"
        color="accent darken-1"
        @click="handleClick"
        :disabled="disabled"
    >
        remove
    </v-btn>
</template>

<script>

    import {mapActions} from 'vuex';
    import Item from "../../../models/Item";

    export default {
        name: "RemoveItemToSellButton",
        props: {
            item: {
                type: Item,
                required: true
            },
            disabled: {
                type: Boolean,
                default: false
            },
            fab: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            ...mapActions([
                'removeItemToSell'
            ]),
            handleClick() {
                this.removeItemToSell(this.item);
                this.$emit('removed');
            }
        }
    }
</script>

<style scoped>

</style>

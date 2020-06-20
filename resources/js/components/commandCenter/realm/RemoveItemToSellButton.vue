<template>
    <v-btn
        x-small
        color="accent"
        @click="handleClick"
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
            }
        },
        methods: {
            ...mapActions([
                'removeItemToSell'
            ]),
            handleClick() {
                this.removeItemToSell(this.item);
            }
        }
    }
</script>

<style scoped>

</style>

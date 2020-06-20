<template>
    <v-btn
        x-small
        color="primary"
        @click="handleClick"
    >
        sell
    </v-btn>
</template>

<script>

    import {mapActions} from 'vuex';
    import Item from "../../../models/Item";

    export default {
        name: "AddItemToSellButton",
        props: {
            item: {
                type: Item,
                required: true
            }
        },
        methods: {
            ...mapActions([
                'addItemToSell'
            ]),
            handleClick() {
                this.addItemToSell(this.item);
            }
        }
    }
</script>

<style scoped>

</style>

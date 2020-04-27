<template>
    <v-btn
        fab
        x-small
        color="success"
        @click="storeItem"
        :disabled="pending"
    >
        <v-icon>unarchive</v-icon>
    </v-btn>
</template>

<script>
    import Item from "../../../models/Item";

    import {mapActions} from 'vuex';
    export default {
        name: "MobileStoreItemButton",
        props: {
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
                'mobileStoreItem'
            ]),
            async storeItem() {
                this.pending = true;
                await this.mobileStoreItem(this.item);
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>

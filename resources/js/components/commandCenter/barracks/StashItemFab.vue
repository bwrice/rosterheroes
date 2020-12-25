<template>
    <v-btn
        fab
        x-small
        color="warning"
        @click="stash"
        :disabled="pending"
    >
        <v-icon>archive</v-icon>
    </v-btn>
</template>

<script>

    import {mapActions} from 'vuex';
    import Item from "../../../models/Item";

    export default {
        name: "StashItemFab",
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
                'stashItem'
            ]),
            async stash() {
                this.pending = true;
                await this.stashItem(this.item);
                this.pending = false;
            }
        }
    }
</script>

<style scoped>

</style>

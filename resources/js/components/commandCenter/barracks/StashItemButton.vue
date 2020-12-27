<template>
    <v-btn
        :fab="fab"
        :x-small="fab"
        color="warning"
        @click="stash"
        :disabled="pending"
    >
        <v-icon :left="! fab">archive</v-icon>
        {{ fab ? '' : 'Stash'}}
    </v-btn>
</template>

<script>

    import {mapActions} from 'vuex';
    import Item from "../../../models/Item";

    export default {
        name: "StashItemButton",
        props: {
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

<template>
    <v-btn
        :fab="fab"
        :x-small="fab"
        color="success"
        @click="storeItem"
        :disabled="pending"
    >
        <v-icon :left="! fab">unarchive</v-icon>
        {{buttonText}}
    </v-btn>
</template>

<script>
    import Item from "../../../models/Item";
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    export default {
        name: "MobileStoreItemButton",
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
                'mobileStoreItem'
            ]),
            async storeItem() {
                this.pending = true;
                await this.mobileStoreItem(this.item);
                this.pending = false;
                this.$emit('stored', {item: this.item})
            }
        },
        computed: {
            ...mapGetters([
                '_mobileStorageRankName'
            ]),
            buttonText() {
                if (this.fab) {
                    return '';
                }
                return this._mobileStorageRankName;
            }
        }
    }
</script>

<style scoped>

</style>

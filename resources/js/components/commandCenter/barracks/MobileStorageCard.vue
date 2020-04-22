<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">{{storageName}}</span>
        </v-col>
        <v-col cols="12">
            <ItemIterator
                :items="_mobileStorage.items"
            ></ItemIterator>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ItemIterator from "../global/ItemIterator";

    export default {
        name: "MobileStorageCard",
        components: {ItemIterator},
        computed: {
            ...mapGetters([
                '_mobileStorage',
                '_mobileStorageRankName',
            ]),
            storageName() {
                return this._mobileStorageRankName.toUpperCase();
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">WAGON</span>
        </v-col>
        <v-col cols="12">
            <ItemIterator
                :items="mobileStorage.items"
            ></ItemIterator>
        </v-col>
    </v-row>
</template>

<script>
    import MobileStorage from "../../../models/MobileStorage";
    import ItemIterator from "../global/ItemIterator";

    export default {
        name: "MobileStorageCard",
        components: {ItemIterator},
        props: {
            mobileStorage: {
                type: MobileStorage,
                required: true
            }
        },
        computed: {
            storageName() {
                return this.mobileStorage.mobileStorageRank.name;
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-card>
        <v-card-title>{{storageName}}</v-card-title>
        <v-card-text>
            <ItemIterator
                :items="mobileStorage.items"
            ></ItemIterator>
        </v-card-text>
    </v-card>
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

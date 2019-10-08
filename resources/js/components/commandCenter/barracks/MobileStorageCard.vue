<template>
    <v-card>
        <v-card-title>{{storageName}}</v-card-title>
        <v-card-text>
            <FilledSlotIterator
                :filled-slots="filledStorageSlots"
            ></FilledSlotIterator>
        </v-card-text>
    </v-card>
</template>

<script>
    import MobileStorage from "../../../models/MobileStorage";
    import FilledSlotPanel from "../global/FilledSlotPanel";
    import FilledSlotIterator from "../global/FilledSlotIterator";

    export default {
        name: "MobileStorageCard",
        components: {FilledSlotIterator, FilledSlotPanel},
        props: {
            mobileStorage: {
                type: MobileStorage,
                required: true
            }
        },
        computed: {
            storageName() {
                return this.mobileStorage.mobileStorageRank.name;
            },
            filledStorageSlots() {
                return this.mobileStorage.filledSlots;
            }
        }
    }
</script>

<style scoped>

</style>

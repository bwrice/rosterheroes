<template>
    <v-card class="my-2">
        <v-card-title>{{storageName}}</v-card-title>
        <v-card-text>
            <v-data-iterator
                :items="filledStorageSlots"
                hide-default-footer
            >
<!--                <template v-slot:header>-->
<!--                    <v-toolbar-->
<!--                        class="mb-2"-->
<!--                        color="secondary"-->
<!--                        dark-->
<!--                        text-->
<!--                    >-->
<!--                        <v-toolbar-title>Select Player Spirit</v-toolbar-title>-->
<!--                    </v-toolbar>-->
<!--                </template>-->
                <template v-slot:item="props">
                    <FilledSlotPanel :filled-slot="props.item"></FilledSlotPanel>
                </template>
            </v-data-iterator>
        </v-card-text>
    </v-card>
</template>

<script>
    import MobileStorage from "../../../models/MobileStorage";
    import FilledSlotPanel from "../global/FilledSlotPanel";

    export default {
        name: "MobileStorageCard",
        components: {FilledSlotPanel},
        props: {
            mobileStorage: {
                type: MobileStorage,
                required: true
            }
        },
        computed: {
            storageName() {
                return this.mobileStorage.mobileStorageRank.name.toUpperCase();
            },
            filledStorageSlots() {
                return this.mobileStorage.filledSlots;
            }
        }
    }
</script>

<style scoped>

</style>

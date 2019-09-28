<template>
    <v-card class="my-2">
        <v-card-title>{{storageName}}</v-card-title>
        <v-card-text>
            <v-data-iterator
                :items="filledStorageSlots"
                :items-per-page="itemsPerPage"
                :page="page"
                hide-default-footer
            >
                <template v-slot:item="props">
                    <FilledSlotPanel :filled-slot="props.item"></FilledSlotPanel>
                </template>
                <template v-slot:footer>
                    <v-row class="mt-2" align="center" justify="center">
                        <div class="flex-grow-1"></div>
                        <span class="mr-4 grey--text">Page {{ page }} of {{ numberOfPages }}</span>
                        <v-btn
                            fab
                            dark
                            x-small
                            color="primary darken-1"
                            class="mr-1"
                            :disabled="formerPageDisabled"
                            @click="formerPage"
                        >
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>
                        <v-btn
                            fab
                            dark
                            x-small
                            color="primary darken-1"
                            class="ml-1"
                            :disabled="nextPageDisabled"
                            @click="nextPage"
                        >
                            <v-icon>mdi-chevron-right</v-icon>
                        </v-btn>
                        <div class="flex-grow-1"></div>
                    </v-row>
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
        data() {
            return {
                page: 1,
                itemsPerPage: 2
            }
        },
        computed: {
            storageName() {
                return this.mobileStorage.mobileStorageRank.name.toUpperCase();
            },
            filledStorageSlots() {
                return this.mobileStorage.filledSlots;
            },
            numberOfPages () {
                return Math.ceil(this.filledStorageSlots.length / this.itemsPerPage)
            },
            formerPageDisabled() {
                return this.page === 1;
            },
            nextPageDisabled() {
                return this.page === this.numberOfPages;
            }
        },
        methods: {
            nextPage () {
                if (this.page + 1 <= this.numberOfPages) this.page += 1
            },
            formerPage () {
                if (this.page - 1 >= 1) this.page -= 1
            },
        }
    }
</script>

<style scoped>

</style>

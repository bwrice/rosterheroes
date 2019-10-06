<template>
    <v-data-iterator
        :items="filteredStorageSlots"
        :items-per-page="itemsPerPage"
        :page="page"
        hide-default-footer
    >
        <template v-slot:header>
            <v-text-field
                v-model="slotSearch"
                clearable
                flat
                solo-inverted
                hide-details
                prepend-inner-icon="search"
                :label="searchLabel"
                class="my-2"
            ></v-text-field>
        </template>
        <template v-slot:item="props">
            <FilledSlotPanel
                :filled-slot="props.item"
                :item-name-truncate-extra="itemNameTruncateExtra"
                :item-card-color="itemCardColor"
            >
                <template v-slot:before-expand="panelProps">
                    <!-- nested scoped slots -->
                    <slot name="before-expand" :item="panelProps.item">
                        <!-- slot:before-expand -->
                    </slot>
                </template>
            </FilledSlotPanel>
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
</template>

<script>
    import * as jsSearch from 'js-search';
    import FilledSlotPanel from "./FilledSlotPanel";

    export default {
        name: "FilledSlotIterator",
        components: {FilledSlotPanel},
        props: {
            filledSlots: {
                type: Array,
                required: true
            },
            searchLabel: {
                type: String,
                default: 'Search Items'
            },
            itemsPerPage: {
                type: Number,
                default: 10
            },
            itemNameTruncateExtra: {
                type: Number,
                default: 0
            },
            itemCardColor: {
                type: String
            }
        },
        data() {
            return {
                page: 1,
                slotSearch: ''
            }
        },
        methods: {
            nextPage () {
                if (this.page + 1 <= this.numberOfPages) this.page += 1
            },
            formerPage () {
                if (this.page - 1 >= 1) this.page -= 1
            },
        },
        computed: {
            numberOfPages () {
                let slotsCount = this.filteredStorageSlots.length;
                if (! slotsCount) return 1;
                return Math.ceil(slotsCount / this.itemsPerPage)
            },
            formerPageDisabled() {
                return this.page === 1;
            },
            nextPageDisabled() {
                return this.page === this.numberOfPages;
            },
            filteredStorageSlots() {
                if (this.slotSearch) {
                    let search = new jsSearch.Search('uuid');
                    search.addIndex(['item', 'name']);
                    search.addIndex(['item', 'itemType', 'name']);
                    search.addIndex(['item', 'itemClass', 'name']);
                    search.addIndex(['item', 'material', 'name']);
                    search.addDocuments(this.filledSlots);
                    return search.search(this.slotSearch);
                } else {
                    return this.filledSlots;
                }
            }
        }
    }
</script>

<style scoped>

</style>

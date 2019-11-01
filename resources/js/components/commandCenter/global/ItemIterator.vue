<template>
    <v-data-iterator
        :items="items"
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
            <IteratorFooter :page="page" :number-of-pages="numberOfPages" @formerPage="decreasePage" @nextPage="increasePage"></IteratorFooter>
        </template>
    </v-data-iterator>
</template>

<script>
    import * as jsSearch from 'js-search';
    import FilledSlotPanel from "./FilledSlotPanel";
    import IteratorFooter from "./IteratorFooter";

    export default {
        name: "ItemIterator",
        components: {IteratorFooter, FilledSlotPanel},
        props: {
            filledSlots: {
                type: Array,
                required: true
            },
            items: {
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
            increasePage () {
                if (this.page + 1 <= this.numberOfPages) this.page += 1
            },
            decreasePage () {
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

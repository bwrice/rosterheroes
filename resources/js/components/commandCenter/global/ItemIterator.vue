<template>
    <v-data-iterator
        :items="filteredItems"
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
            <ItemExpandPanel
                :item="props.item"
                :item-name-truncate-extra="itemNameTruncateExtra"
                :item-card-color="itemCardColor"
            >
                <template v-slot:before-expand="panelProps">
                    <!-- nested scoped slots -->
                    <slot name="before-expand" :item="panelProps.item">
                        <!-- slot:before-expand -->
                    </slot>
                </template>
            </ItemExpandPanel>
        </template>
        <template v-slot:footer>
            <IteratorFooter :page="page" :number-of-pages="numberOfPages" @formerPage="decreasePage" @nextPage="increasePage"></IteratorFooter>
        </template>
    </v-data-iterator>
</template>

<script>
    import * as jsSearch from 'js-search';
    import ItemExpandPanel from "./ItemExpandPanel";
    import IteratorFooter from "./IteratorFooter";

    export default {
        name: "ItemIterator",
        components: {IteratorFooter, ItemExpandPanel},
        props: {
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
                let itemsCount = this.filteredItems.length;
                if (! itemsCount) return 1;
                return Math.ceil(itemsCount / this.itemsPerPage)
            },
            formerPageDisabled() {
                return this.page === 1;
            },
            nextPageDisabled() {
                return this.page === this.numberOfPages;
            },
            filteredItems() {
                if (this.slotSearch) {
                    let search = new jsSearch.Search('uuid');
                    search.addIndex(['name']);
                    search.addIndex(['itemType', 'name']);
                    search.addIndex(['itemClass', 'name']);
                    search.addIndex(['material', 'name']);
                    search.addDocuments(this.items);
                    return search.search(this.slotSearch);
                } else {
                    return this.items;
                }
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <PaginationBlock :items="items" :search="search">
        <template v-slot:default="slotProps">
            <ItemExpandPanel
                :item="slotProps.item"
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
    </PaginationBlock>
</template>

<script>
    import * as jsSearch from 'js-search';
    import ItemExpandPanel from "./ItemExpandPanel";
    import IteratorFooter from "./IteratorFooter";
    import PaginationBlock from "./PaginationBlock";

    export default {
        name: "ItemIterator",
        components: {PaginationBlock, IteratorFooter, ItemExpandPanel},
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
                search: {
                    label: 'Search Wagon',
                    search: function (items, input) {
                        let search = new jsSearch.Search('uuid');
                        search.addIndex(['name']);
                        search.addIndex(['itemType', 'name']);
                        search.addIndex(['itemClass', 'name']);
                        search.addIndex(['material', 'name']);
                        search.addDocuments(items);
                        return search.search(input);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

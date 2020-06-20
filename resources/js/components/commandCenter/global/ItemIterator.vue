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
    import PaginationBlock from "./PaginationBlock";

    export default {
        name: "ItemIterator",
        components: {PaginationBlock, ItemExpandPanel},
        props: {
            items: {
                type: Array,
                required: true
            },
            itemNameTruncateExtra: {
                type: Number,
                default: 0
            },
            itemCardColor: {
                type: String
            },
            searchLabel: {
                type: String,
                default: 'Search Wagon'
            },
            withSearch: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            search() {
                if (! this.withSearch) {
                    return null;
                }
                return {
                    label: this.searchLabel,
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

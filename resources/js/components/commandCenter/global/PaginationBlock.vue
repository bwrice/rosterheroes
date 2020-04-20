<template>
    <v-data-iterator
        :items="filteredItems"
        :items-per-page="itemsPerPage"
        :page="page"
        hide-default-footer
    >
        <template v-slot:header v-if="search">
            <v-text-field
                v-model="searchInput"
                clearable
                flat
                solo-inverted
                hide-details
                prepend-inner-icon="search"
                :label="search.label"
                class="my-2"
            ></v-text-field>
        </template>
        <template v-slot:item="props">
            <slot :item="props.item">

            </slot>
        </template>
        <template v-slot:footer>
            <IteratorFooter :page="page" :number-of-pages="numberOfPages" @formerPage="decreasePage" @nextPage="increasePage"></IteratorFooter>
        </template>
    </v-data-iterator>
</template>

<script>
    import IteratorFooter from "./IteratorFooter";

    export default {
        name: "PaginationBlock",
        components: {IteratorFooter},
        props: {
            items: {
                type: Array,
                required: true
            },
            itemsPerPage: {
                type: Number,
                default: 10
            },
            search: {
                type: Object
            }
        },
        data() {
            return {
                page: 1,
                searchInput: ''
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
            numberOfPages() {
                let itemsCount = this.filteredItems.length;
                if (!itemsCount) return 1;
                return Math.ceil(itemsCount / this.itemsPerPage)
            },
            formerPageDisabled() {
                return this.page === 1;
            },
            nextPageDisabled() {
                return this.page === this.numberOfPages;
            },
            filteredItems() {
                if (this.search && this.searchInput) {
                    return this.search.search(this.items, this.searchInput);
                }
                return this.items;
            }
        }
    }
</script>

<style scoped>

</style>

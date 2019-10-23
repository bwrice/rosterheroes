<template>
    <v-data-iterator
        :items="spells"
        :items-per-page="itemsPerPage"
        :page="page"
        hide-default-footer
    >
        <template v-slot:item="props">
            <SpellPanel :spell="props.item">
                <template v-slot:before-mana-cost="panelProps">
                    <!-- nested scoped slots -->
                    <slot name="before-mana-cost" :item="panelProps.spell">
                        <!-- slot:before-mana-cost -->
                    </slot>
                </template>
            </SpellPanel>
        </template>
        <template v-slot:footer>
            <IteratorFooter :page="page" :number-of-pages="numberOfPages" @formerPage="decreasePage" @nextPage="increasePage"></IteratorFooter>
        </template>
    </v-data-iterator>
</template>

<script>
    import SpellPanel from "./SpellPanel";
    import IteratorFooter from "../global/IteratorFooter";
    export default {
        name: "SpellPanelIterator",
        components: {IteratorFooter, SpellPanel},
        props: {
            spells: {
                type: Array,
                required: true
            },
            itemsPerPage: {
                type: Number,
                default: 10
            }
        },
        data() {
            return {
                page: 1
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
                let spellsCount = this.spells.length;
                if (!spellsCount) return 1;
                return Math.ceil(spellsCount / this.itemsPerPage)
            },
            formerPageDisabled() {
                return this.page === 1;
            },
            nextPageDisabled() {
                return this.page === this.numberOfPages;
            },
        }
    }
</script>

<style scoped>

</style>

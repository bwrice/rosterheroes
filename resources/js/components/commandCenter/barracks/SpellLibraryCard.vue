<template>
    <v-card>
        <v-card-title>Spell Library</v-card-title>
        <v-card-text>
            <v-data-iterator
                :items="spells"
                :items-per-page="itemsPerPage"
                :page="page"
                hide-default-footer
            >
                <template v-slot:item="props">
                    <SpellPanel :spell="props.item"></SpellPanel>
                </template>
                <template v-slot:footer>
                    <IteratorFooter :page="page" :number-of-pages="numberOfPages" @formerPage="decreasePage" @nextPage="increasePage"></IteratorFooter>
                </template>
            </v-data-iterator>
        </v-card-text>
    </v-card>
</template>

<script>
    import IteratorFooter from "../global/IteratorFooter";
    import SpellPanel from "./SpellPanel";
    export default {
        name: "SpellLibraryCard",
        components: {SpellPanel, IteratorFooter},
        props: {
            spells: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                page: 1,
                itemsPerPage: 10
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

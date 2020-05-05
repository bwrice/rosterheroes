<template>
    <PaginationBlock
        :items-per-page="6"
        :items="provinces"
        :search="search"
    >
        <template v-slot:default="slotProps">
            <ProvincePanel :province="slotProps.item"></ProvincePanel>
        </template>
    </PaginationBlock>
</template>

<script>
    import * as jsSearch from 'js-search';
    import PaginationBlock from "../global/PaginationBlock";
    import ProvincePanel from "./ProvincePanel";
    export default {
        name: "ProvincePaginationBlock",
        components: {ProvincePanel, PaginationBlock},
        props: {
            provinces: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                search: {
                    label: this.searchLabel,
                    search: function (provinces, input) {
                        let search = new jsSearch.Search('uuid');
                        search.addIndex(['name']);
                        search.addDocuments(provinces);
                        return search.search(input);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

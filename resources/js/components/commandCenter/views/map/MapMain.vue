<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-card>
            <svg xmlns="http://www.w3.org/2000/svg"
                 version="1.1"
                 viewBox="0 0 315 240">

                <rect width="315" height="240" rx="5" ry="5"
                      style="fill: #c9e5ea; stroke: #E3EFEB; stroke-width: 2"
                ></rect>
                <ProvinceVector v-for="(province, uuid) in this._provinces" :key="uuid" :province="province"></ProvinceVector>
            </svg>
        </v-card>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "MapMain",
        components: {ProvinceVector},

        data: function() {
            return {
                mode: 'continent'
            }
        },
        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents'
            ])
        }
    }
</script>

<style scoped>

</style>
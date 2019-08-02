<template>
    <g>
        <ProvinceVector v-for="(province, uuid) in provincesForContinent" :key="uuid" :province="province"></ProvinceVector>
    </g>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "ContinentVector",
        components: {ProvinceVector},
        props: ['continent'],

        mounted: function() {
            console.log("Continent");
            console.log(this.continent);
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents'
            ]),
            provincesForContinent() {
                let continentProvinces = [];
                let self = this;
                this._provinces.forEach(function(province) {
                    if (province.continent_id === self.continent.id) {
                        continentProvinces.push(province);
                    }
                });
                return continentProvinces;
            }
        }
    }
</script>

<style scoped>

</style>
<template>
    <router-link :to="continentRoute">
        <g :fill="fillColor" :opacity="opacity" @mouseover="setHovered(true)" @mouseleave="setHovered(false)">
            <ProvinceVector v-for="(province, uuid) in provincesForContinent" :key="uuid" :province="province"></ProvinceVector>
        </g>
    </router-link>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "ContinentVector",
        components: {ProvinceVector},
        props: ['continent'],

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            }
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents',
                '_squad'
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
            },
            fillColor() {
                return this.continent.realm_color;
            },
            opacity() {
                if (this.hovered) {
                    return .6;
                }
                return 1;
            },
            continentRoute() {
                return {
                    name: 'map-continent',
                    params: {
                        squadSlug: this._squad.slug,
                        continentSlug: this.continent.slug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
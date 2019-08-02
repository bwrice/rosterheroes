<template>
    <g :fill="fillColor" :opacity="opacity" @mouseover="setHovered(true)" @mouseleave="setHovered(false)" @click="navigateToContinent">
        <ProvinceVector v-for="(province, uuid) in provincesForContinent" :key="uuid" :province="province"></ProvinceVector>
    </g>
</template>

<script>

    import {mapActions} from 'vuex';
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
            ...mapActions([
                'updateContinent',
            ]),
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            },
            navigateToContinent() {
                this.updateContinent(this.continent);
                this.$router.push(this.continentRoute);
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
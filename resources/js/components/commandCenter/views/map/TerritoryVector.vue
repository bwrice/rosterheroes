<template>
    <g @mouseover="setHovered(true)" @mouseleave="setHovered(false)" @click="navigateToTerritory">
        <ProvinceVector
                v-for="(province, uuid) in provincesForTerritory"
                :key="uuid"
                :province="province"
                :fill-color="fillColor"
                :opacity="opacity"
        >
        </ProvinceVector>
    </g>
</template>

<script>

    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "TerritoryVector",
        components: {ProvinceVector},
        props: ['territory'],

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            ...mapActions([

            ]),
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            },
            navigateToTerritory() {
                //TODO
            }
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_territories'
            ]),
            fillColor() {
                return this.territory.realm_color;
            },
            opacity() {
                if (this.hovered) {
                    return .6;
                }
                return 1;
            },
            provincesForTerritory() {
                let territoryProvinces = [];
                let self = this;
                this._provinces.forEach(function(province) {
                    if (province.territory_id === self.territory.id) {
                        territoryProvinces.push(province);
                    }
                });
                return territoryProvinces;
            },
        }
    }
</script>

<style scoped>

</style>
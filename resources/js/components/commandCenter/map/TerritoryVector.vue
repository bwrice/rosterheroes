<template>
    <g @mouseover="setHovered(true)" @mouseleave="setHovered(false)" @click="navigateToTerritory">
        <ProvinceVector
                v-for="(province, uuid) in provincesForTerritory"
                :key="uuid"
                :province="province"
                :fill-color="fillColor"
                :parent-hovered="hovered"
        >
        </ProvinceVector>
    </g>
</template>

<script>

    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';
    import { territoryMixin } from '../../../mixins/territoryMixin';
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "TerritoryVector",
        components: {ProvinceVector},
        props: ['territory'],
        mixins: [
            territoryMixin
        ],

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            ...mapActions([
                'updateTerritory'
            ]),
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            },
            navigateToTerritory() {
                this.updateTerritory(this.territory);
                this.$router.push(this.territoryRoute)
            }
        },

        computed: {
            ...mapGetters([
                '_squad',
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
            territoryRoute() {
                return {
                    name: 'map-territory',
                    params: {
                        squadSlug: this._squad.slug,
                        territorySlug: this.territory.slug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

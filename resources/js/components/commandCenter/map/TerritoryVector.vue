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

    import {mapGetters} from 'vuex';
    import { territoryMixin } from '../../../mixins/territoryMixin';
    import ProvinceVector from "./ProvinceVector";
    import Territory from "../../../models/Territory";

    export default {
        name: "TerritoryVector",
        components: {ProvinceVector},
        props: {
            territory: {
                type: Territory,
                required: true
            }
        },

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            },
            navigateToTerritory() {
                this.$router.push(this.territoryRoute)
            }
        },

        computed: {
            ...mapGetters([
                '_provincesByTerritoryID'
            ]),
            fillColor() {
                return this.territory.realmColor;
            },
            opacity() {
                if (this.hovered) {
                    return .6;
                }
                return 1;
            },
            provincesForTerritory() {
                return this._provincesByTerritoryID(this.territory.id);
            },
            territoryRoute() {
                return {
                    name: 'explore-territory',
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

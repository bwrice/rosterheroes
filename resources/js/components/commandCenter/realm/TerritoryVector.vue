<template>
    <g @mouseover="setHovered(true)" @mouseleave="setHovered(false)" @click="navigateToTerritory">
        <ProvinceVector
                v-for="(province, uuid) in provinces"
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
    import ProvinceVector from "./ProvinceVector";
    import Territory from "../../../models/Territory";

    export default {
        name: "TerritoryVector",
        components: {ProvinceVector},
        props: {
            territory: {
                type: Territory,
                required: true
            },
            interactive: {
                type: Boolean,
                default: true
            }
        },

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            setHovered: function(hoveredState) {
                if (this.interactive) {
                    this.hovered = hoveredState;
                }
            },
            navigateToTerritory() {
                if (this.interactive) {
                    this.territory.goToRoute(this.$router, this.$route);
                }
            }
        },

        computed: {
            ...mapGetters([
                '_provincesByTerritoryID'
            ]),
            fillColor() {
                return this.territory.color;
            },
            opacity() {
                if (this.hovered) {
                    return .6;
                }
                return 1;
            },
            provinces() {
                return this._provincesByTerritoryID(this.territory.id);
            },
        }
    }
</script>

<style scoped>

</style>

<template>
    <g @mouseover="setHovered(true)" @mouseleave="setHovered(false)" @click="navigateToContinent" :class="[interactive ? 'rh-clickable' : '']">
        <ProvinceVector
                v-for="(province, uuid) in provincesForContinent"
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
    import { continentMixin } from '../../../mixins/continentMixin';
    import ProvinceVector from "./ProvinceVector";
    import Continent from "../../../models/Continent";

    export default {
        name: "ContinentVector",
        components: {ProvinceVector},
        props: {
            continent: {
                type: Continent,
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
            navigateToContinent() {
                if (this.interactive) {
                    this.$router.push(this.continent.getRoute(this.$route));
                }
            }
        },

        computed: {
            ...mapGetters([
                '_provincesByContinentID'
            ]),
            fillColor() {
                return this.continent.color;
            },
            provincesForContinent() {
                return this._provincesByContinentID(this.continent.id);
            }
        }
    }
</script>

<style scoped>

</style>

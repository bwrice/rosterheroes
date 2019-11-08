<template>
    <g @mouseover="setHovered(true)" @mouseleave="setHovered(false)" @click="navigateToContinent">
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
            }
        },
        // mixins: [
        //     continentMixin
        // ],

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            },
            navigateToContinent() {
                this.$router.push(this.continentRoute);
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
            },
            continentRoute() {
                return {
                    name: 'explore-continent',
                    params: {
                        squadSlug: this.$route.params.squadSlug,
                        continentSlug: this.continent.slug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

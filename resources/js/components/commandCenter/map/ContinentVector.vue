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

    export default {
        name: "ContinentVector",
        components: {ProvinceVector},
        props: ['continent'],
        mixins: [
            continentMixin
        ],

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
                '_provinces',
                '_continents',
                '_squad'
            ]),
            fillColor() {
                return this.continent.realm_color;
            },
            continentRoute() {
                return {
                    name: 'explore-continent',
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

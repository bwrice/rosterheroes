<template>
    <g
        v-html="province.vector_paths"
        :fill="fill" :opacity="opacity"
        @click="navigateToProvince"
        :stroke="stroke"
        stroke-width=".5"
        stroke-opacity=".9"
        @mouseover="setHovered(true)"
        @mouseleave="setHovered(false)"
    >
    </g>
</template>

<script>
    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';

    export default {
        name: "ProvinceVector",
        props: {
            province: {
                required: true
            },
            fillColor: {
                type: String,
            },
            parentHovered: {
                type: Boolean,
                default: false
            },
            routeLink: {
                type: Boolean,
                default: false
            },
            highlight: {
                type: Boolean,
                default: false
            }
        },

        data: function() {
            return {
                hovered: false
            }
        },

        methods: {
            ...mapActions([
                'setProvince'
            ]),
            setHovered: function(hoveredState) {
                this.hovered = hoveredState;
            },
            navigateToProvince() {
                if (this.routeLink) {
                    this.setProvince(this.province);
                    this.$router.push(this.provinceRoute);
                }
            }
        },

        computed: {
            ...mapGetters([
                '_squad'
            ]),
            fill() {
                if (this.fillColor) {
                    return this.fillColor;
                }
                return this.province.color;
            },
            provinceRoute() {
                return {
                    name: 'map-province',
                    params: {
                        squadSlug: this._squad.slug,
                        provinceSlug: this.province.slug
                    }
                }
            },

            opacity() {
                if (this.parentHovered || (this.routeLink && this.hovered)) {
                    return .6;
                }
                return 1;
            },
            stroke() {
                if (this.highlight) {
                    return '#FFFFFF';
                }
                return 'none';
            }
        }
    }
</script>

<style scoped>

</style>

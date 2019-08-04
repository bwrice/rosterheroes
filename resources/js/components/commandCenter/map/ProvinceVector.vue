<template>
    <g v-html="province.vector_paths" :fill="fill" :opacity="opacity" @click="navigateToProvince" :stroke="stroke" stroke-width=".5" stroke-opacity=".9">
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
            opacity: {
                type: Number,
                default: 1
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

        methods: {
            ...mapActions([
                'setProvince'
            ]),

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

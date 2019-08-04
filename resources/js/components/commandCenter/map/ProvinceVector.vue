<template>
    <g v-html="province.vector_paths" :fill="fill" :opacity="opacity" @click="navigateToProvince">
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
            }
        }
    }
</script>

<style scoped>

</style>

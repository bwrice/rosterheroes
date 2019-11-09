<template>
    <g
        v-html="province.vectorPaths"
        :fill="fill"
        :opacity="opacity"
        :stroke="stroke"
        stroke-width=".5"
        stroke-opacity=".9"
        @click="clicked"
        @mouseover="setHovered(true)"
        @mouseleave="setHovered(false)"
    >
    </g>
</template>

<script>
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
            hoverable: {
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
            setHovered (hoveredState) {
                this.hovered = hoveredState;
            },
            clicked (event) {
                this.$emit('provinceClicked', this.province, event);
            }
        },

        computed: {
            fill() {
                if (this.fillColor) {
                    return this.fillColor;
                }
                return this.province.color;
            },

            opacity() {
                if (this.parentHovered || (this.hoverable && this.hovered)) {
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

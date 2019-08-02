<template>
    <g v-html="province.vector_paths" :fill="fillColor">
    </g>
</template>

<script>
    export default {
        name: "ProvinceVector",
        props: {
            province: {
                required: true
            },
            overrideColor: {
                type: String
            }
        },
        computed: {
            fillColor() {
                if (this.overrideColor) {
                    return this.overrideColor;
                }
                return this.province.color;
            }
        }
    }
</script>

<style scoped>

</style>
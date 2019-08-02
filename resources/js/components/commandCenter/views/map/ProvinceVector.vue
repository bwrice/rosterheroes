<template>
    <g v-html="province.vector_paths" :fill="fill" :opacity="opacity">
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
            opacity: {
                type: Number,
                default: 1
            }
        },
        computed: {
            fill() {
                if (this.fillColor) {
                    return this.fillColor;
                }
                return this.province.color;
            }
        }
    }
</script>

<style scoped>

</style>
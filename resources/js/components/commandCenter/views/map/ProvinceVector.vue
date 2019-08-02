<template>
    <g v-html="province.vector_paths">
    </g>
</template>

<script>
    export default {
        name: "ProvinceVector",
        props: {
            province: {
                required: true
            }
        },
        computed: {

        }
    }
</script>

<style scoped>

</style>
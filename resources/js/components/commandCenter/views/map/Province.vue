<template>
    <g v-html="province.vector_paths">
    </g>
</template>

<script>
    export default {
        name: "Province",
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
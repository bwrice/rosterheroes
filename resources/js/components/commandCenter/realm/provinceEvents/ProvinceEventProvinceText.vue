<template>
    <span class="accent--text">{{province.name}}</span>
</template>

<script>
    import Province from "../../../../models/Province";

    export default {
        name: "ProvinceEventProvinceText",
        props: {
            province: {
                type: Province,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <span class="primary--text">{{name}}</span>
</template>

<script>
    export default {
        name: "ProvinceEventSquadText",
        props: {
            name: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

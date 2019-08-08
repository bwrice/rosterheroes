<template>
    <v-sheet tile :color="color" class="pa-1">
        {{province.name}}
    </v-sheet>
</template>

<script>
    export default {
        name: "TravelRouteListItem",
        props: {
            province: {
                type: Object,
                required: true
            },
            color: {
                type: String,
                default: 'success'
            }
        }
    }
</script>

<style scoped>

</style>

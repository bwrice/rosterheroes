<template>
    <v-sheet flat color="#706856" class="ma-1 pa-1">
        {{name}}
    </v-sheet>
</template>

<script>
    export default {
        name: "MeasurablePanel",
        props: {
            measurable: Object,
            required: true
        },
        computed: {
            name() {
                return this.measurable.measurable_type.name;
            }
        }
    }
</script>

<style scoped>

</style>

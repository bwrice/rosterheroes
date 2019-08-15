<template>
    <v-sheet>
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

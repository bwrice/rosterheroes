<template>
    <v-sheet flat color="#706856" class="pa-1 ma-1">
        <v-row no-gutters justify="space-between">
            <p>{{name}}</p>
            <v-btn @click="clicked">+</v-btn>
        </v-row>
    </v-sheet>
</template>

<script>
    export default {
        name: "MeasurablePanel",
        props: {
            measurable: Object,
            required: true
        },
        methods: {
            clicked(event) {
                this.$emit('measurableClicked', this.measurable, event);
            }
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

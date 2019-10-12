<template>
    <v-progress-linear
        height="16"
        :value="value"
        background-color="#ccc"
        background-opacity=".2"
        class="my-1 energy-bar"
    >
        {{energy}}
    </v-progress-linear>
</template>

<script>
    export default {
        name: "SpiritEnergyBar",
        props: {
            energy: {
                type: Number,
                required: true
            }
        },
        computed: {
            value() {
                return Math.ceil( Math.sqrt(this.energy) * 5);
            },
        }
    }
</script>

<style scoped>

    .v-application .primary[style] {
        background-color: red;
    }
</style>

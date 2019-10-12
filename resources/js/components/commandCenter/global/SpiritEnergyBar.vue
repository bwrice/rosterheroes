<template>
    <div class="my-1">
        <GradientBar
            :percent="percent"
            :height="16"
        >
            <span class="font-weight-bold">{{energy}}</span>
        </GradientBar>
    </div>
</template>

<script>
    import GradientBar from "./GradientBar";
    export default {
        name: "SpiritEnergyBar",
        components: {GradientBar},
        props: {
            energy: {
                type: Number,
                required: true
            }
        },
        computed: {
            percent() {
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

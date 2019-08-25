<template>
    <g :fill="svgFill" :fill-opacity="svgFillOpacity" :stroke="svgStroke" :stroke-width="svgStrokeWidth">
        <slot>
            <!-- default slot for svg paths-->
        </slot>
    </g>
</template>

<script>
    export default {
        name: "HeroGearSlotSVG",
        props: {
            heroSlot: {
                typ: Object,
                required: true
            }
        },
        computed: {
            empty() {
                return ! this.heroSlot.item;
            },
            svgFill() {
                if (this.empty) {
                    return '#fff';
                }
                return '#ffc747';
            },
            svgStroke() {
                return this.empty? '#000' : '';
            },
            svgStrokeWidth() {
                return this.empty? .5769 : 2.3;
            },
            svgFillOpacity() {
                return this.empty? .1 : 1;
            }
        }
    }
</script>

<style scoped>

</style>

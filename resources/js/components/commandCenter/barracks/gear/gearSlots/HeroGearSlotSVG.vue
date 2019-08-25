<template>
    <g :fill="svgFill" :stroke="svgStroke" stroke-width=".5769">
        <slot>
            <!-- default slot for svg paths-->
        </slot>
    </g>
</template>

<script>
    export default {
        name: "GearSlotSVG",
        props: {
            slot: {
                typ: Object,
                required: true
            }
        },
        computed: {
            empty() {
                return ! this.slot.item;
            },
            svgFill() {
                if (this.empty) {
                    return '#c7c7c7';
                }
                return '#ffc747';
            },
            svgStroke() {
                if (this.empty) {
                    return '#000';
                }
                return '#fff';
            }
        }
    }
</script>

<style scoped>

</style>

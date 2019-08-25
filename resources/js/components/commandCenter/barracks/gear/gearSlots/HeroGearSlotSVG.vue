<template>
    <g :fill="svgFill" :fill-opacity="svgFillOpacity" :stroke="svgStroke" :stroke-width="svgStrokeWidth" :stroke-opacity="svgStrokeOpacity">
        <slot>
            <!-- default slot for svg paths-->
        </slot>
    </g>
</template>

<script>
    export default {
        name: "HeroGearSlotSVG",
        props: {
            heroSlots: {
                type: Array,
                default: []
            },
            name: {
                type: String,
                required: true
            }
        },
        computed: {
            heroSlot() {
                let heroSlot = this.heroSlots.find(slot => slot.slotType.name === this.name);
                if (heroSlot) {
                    return heroSlot;
                }
                return {
                    item: null,
                    slotType: {
                        name : ''
                    }
                }
            },
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
                return '#000';
            },
            svgStrokeWidth() {
                return this.empty? 2.3 : 2.3;
            },
            svgFillOpacity() {
                return this.empty? .15 : 1;
            },
            svgStrokeOpacity() {
                return this.empty? .6 : 1;
            }
        }
    }
</script>

<style scoped>

</style>

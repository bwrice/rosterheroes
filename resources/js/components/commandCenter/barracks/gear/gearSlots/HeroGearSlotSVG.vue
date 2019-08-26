<template>
    <g>
        <g
            :fill="svgFill"
            :fill-opacity="svgFillOpacity"
            :stroke="svgStroke"
            :stroke-width="svgStrokeWidth"
            :stroke-opacity="svgStrokeOpacity"
        >
            <slot>
                <!-- default slot for svg paths-->
            </slot>
        </g>
        <g @click="emitHeroSlotClicked" fill="#fff" fill-opacity=".4" stroke="#000" stroke-width="2">
            <slot name="click-area">
                <!-- slot for clickable rectangle(s)-->
            </slot>
        </g>
    </g>
</template>

<script>
    import Slot from "../../../../../models/Slot";

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
        methods: {
            emitHeroSlotClicked() {
                this.$emit('heroSlotClicked', {
                    heroSlot: this.heroSlot
                })
            }
        },
        computed: {
            heroSlot() {
                let heroSlot = this.heroSlots.find(slot => slot.slotType.name === this.name);
                return heroSlot ? new Slot(heroSlot) : new Slot({});
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

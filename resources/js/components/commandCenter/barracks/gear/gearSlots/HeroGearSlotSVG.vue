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
        <g @click="emitHeroSlotClicked" fill-opacity="0">
            <slot name="click-area">
                <!-- slot for clickable rectangle(s)-->
            </slot>
        </g>
    </g>
</template>

<script>
    import GearSlot from "../../../../../models/GearSlot";

    export default {
        name: "HeroGearSlotSVG",
        props: {
            gearSlots: {
                type: Array,
                default: []
            },
            gearSlotType: {
                type: String,
                required: true
            }
        },
        methods: {
            emitHeroSlotClicked() {
                this.$emit('heroSlotClicked', {
                    gearSlot: this.gearSlot
                })
            }
        },
        computed: {
            gearSlot() {
                let gearSlot = this.gearSlots.find(gearSlot => gearSlot.type === this.gearSlotType);
                return gearSlot ? gearSlot : new GearSlot({});
            },
            empty() {
                return ! this.gearSlot.item;
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

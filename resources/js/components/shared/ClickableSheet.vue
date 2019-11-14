<template>
    <v-sheet
        :color="computedColor"
        :elevation="elevation"
        :class="classesObject"
        @mouseenter="hovered = true"
        @mouseleave="hovered = false"
        @click="emitClick"
    >
        <slot>
            <!-- default slot -->
        </slot>
    </v-sheet>
</template>

<script>
    export default {
        name: "ClickableSheet",
        props: {
            color: {
                type: String,
                default: 'none'
            },
            hoveredColor: {
                type: String,
                default: null
            },
            classesObject: {
                type: Object,
                default: function() {
                    return {}
                }
            }
        },
        data() {
            return {
                hovered: false
            }
        },
        methods: {
            emitClick(e) {
                this.$emit('click', e);
            }
        },
        computed: {
            computedColor() {
                let hoveredColor = this.hoveredColor ? this.hoveredColor : this.color;
                return this.hovered ? hoveredColor : this.color;
            },
            elevation() {
                return this.hovered ? 24 : 4;
            },
        }
    }
</script>

<style scoped>

</style>

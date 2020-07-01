<template>
    <v-sheet
        :color="color"
        :elevation="elevation"
        :class="classes"
        class="rounded"
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
            panelOverrides: {
                type: Object,
                default: function () {
                    return {};
                }
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
                hovered: false,
                panelObject: {
                    color: '#5c707d',
                    hoveredColor: '#6f808c',
                    elevation: 4,
                    hoveredElevation: 12,
                }
            }
        },
        methods: {
            emitClick(e) {
                this.$emit('click', e);
            }
        },
        computed: {
            panel() {
                return _.merge(this.panelObject, this.panelOverrides);
            },
            color() {
                return this.hovered ? this.panel.hoveredColor : this.panel.color;
            },
            elevation() {
                return this.hovered ? this.panel.hoveredElevation : this.panel.elevation;
            },
            classes() {
                let classes = {
                    'rh-clickable' : true
                };
                return _.merge(classes, this.classesObject);
            }
        }
    }
</script>

<style scoped>

</style>

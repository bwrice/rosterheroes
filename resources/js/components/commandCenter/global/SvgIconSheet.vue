<template>
    <v-sheet :color="color" class="svg-icon" :class="classesObject" v-html="svg">
        <!-- SVG rendered through v-html-->
    </v-sheet>
</template>

<script>

    export default {
        name: "SvgIconSheet",
        props: {
            classesObject: {
                type: Object,
                default: function() {
                    return {}
                }
            },
            color: {
                type: String,
                default: 'rgba(0, 0, 0, 0.3)'
            },
            svg: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>
    .svg-icon {
        margin: 2px;
    }
</style>

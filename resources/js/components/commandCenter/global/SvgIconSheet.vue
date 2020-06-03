<template>
    <v-sheet
        :elevation="elevation"
        :color="color"
        style="margin: 2px"
    >
        <slot>
            <!-- Default Slot -->
        </slot>
    </v-sheet>
</template>

<script>

    export default {
        name: "SvgIconSheet",
        props: {
            color: {
                type: String,
                default: 'rgba(0, 0, 0, 0.3)'
            },
            elevation: {
                type: Number,
                default: 0
            }
        }
    }
</script>

<style scoped>
    .svg-icon {
        margin: 2px;
    }
</style>

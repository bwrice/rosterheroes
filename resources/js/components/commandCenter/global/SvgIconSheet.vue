<template>
    <v-sheet :color="color" class="attack-icon" :class="classesObject">
        <v-img
            :src="svgIcon.src"
            :alt="svgIcon.alt"
        >
        </v-img>
    </v-sheet>
</template>

<script>
    import SvgIcon from "../../../models/SvgIcon";

    export default {
        name: "SvgIconSheet",
        props: {
            svgIcon: {
                type: SvgIcon,
                required: true
            },
            classesObject: {
                type: Object,
                default: function() {
                    return {}
                }
            },
            color: {
                type: String,
                default: 'rgba(0, 0, 0, 0.3)'
            }
        }
    }
</script>

<style scoped>
    .attack-icon {
        margin: 2px;
    }
</style>

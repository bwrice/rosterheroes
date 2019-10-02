<template>
    <v-sheet :color="color" class="attack-icon">
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
            color: {
                type: String,
                default: '(255,255,255,0.3)'
            }
        }
    }
</script>

<style scoped>
    .attack-icon {
        margin: 2px;
    }
</style>

<template>
    <v-overlay :value="showOverlay">
        <v-progress-circular
            size="64"
            indeterminate
            color="primary"
        ></v-progress-circular>
    </v-overlay>
</template>

<script>
    export default {
        name: "LoadingOverlay",
        props: {
            showOverlay: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style scoped>

</style>

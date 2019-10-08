<template>
    <v-overlay :value="showOverlay">
        <v-progress-circular
            size="96"
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
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

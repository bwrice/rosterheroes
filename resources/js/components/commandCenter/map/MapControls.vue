<template>
    <v-layout align-space-between wrap>
        <v-flex class="xs6">
            <v-layout justify-center>
                <v-btn fab small @click="emitPanUp">
                    <v-icon dark>arrow_drop_up</v-icon>
                </v-btn>
            </v-layout>
            <v-layout justify-space-around>
                <v-btn fab small @click="emitPanLeft">
                    <v-icon dark>arrow_left</v-icon>
                </v-btn>
                <v-btn fab small @click="emitPanRight">
                    <v-icon dark>arrow_right</v-icon>
                </v-btn>
            </v-layout>
            <v-layout justify-center>
                <v-btn fab small @click="emitPanDown">
                    <v-icon dark>arrow_drop_down</v-icon>
                </v-btn>
            </v-layout>
        </v-flex>
        <v-flex class="xs6">
            <v-layout justify-center class="pa-1">
                <v-btn fab small @click="emitZoomIn">
                    <v-icon dark>add</v-icon>
                </v-btn>
            </v-layout>
            <v-layout justify-center class="pa-1">
                <v-btn fab small @click="emitZoomOut">
                    <v-icon dark>remove</v-icon>
                </v-btn>
            </v-layout>
            <v-layout justify-center>
                <v-btn small @click="emitReset">
                    Reset
                </v-btn>
            </v-layout>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "MapControls",
        methods: {
            emitPanUp(event) {
                this.$emit('panUp', event);
            },
            emitPanDown(event) {
                this.$emit('panDown', event);
            },
            emitPanRight(event) {
                this.$emit('panRight', event);
            },
            emitPanLeft(event) {
                this.$emit('panLeft', event);
            },
            emitZoomIn(event) {
                this.$emit('zoomIn', event);
            },
            emitZoomOut(event) {
                this.$emit('zoomOut', event);
            },
            emitReset(event) {
                this.$emit('reset', event);
            },
        }
    }
</script>

<style scoped>

</style>

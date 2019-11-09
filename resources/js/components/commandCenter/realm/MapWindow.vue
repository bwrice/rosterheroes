<template>
    <rect :x="xValue" :y="yValue" :width="width" :height="height" rx="2" style="stroke-width: 2; stroke: #ff4747; stroke-opacity: 1; fill-opacity: 0;"></rect>
</template>

<script>
    import ViewBox from "../../../models/ViewBox";

    export default {
        name: "MapWindow",
        props: {
            viewBox: {
                type: ViewBox,
                required: true
            }
        },
        computed: {
            xValue() {
                return this.viewBox.panX;
            },
            yValue() {
                return this.viewBox.panY;
            },
            width() {
                return this.viewBox.zoomX
            },
            height() {
                return this.viewBox.zoomY
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <svg xmlns="http://www.w3.org/2000/svg"
         version="1.1"
         :viewBox="viewBox">

        <rect width="315" height="240" rx="5" ry="5"
              style="fill: #c9e5ea; stroke: #E3EFEB; stroke-width: 2"
        ></rect>
        <slot>
            <!-- ProvinceVector components slotted here -->
        </slot>
    </svg>
</template>

<script>
    export default {
        name: "Realm",
        props: {
            panX: {
                type: Number,
                default: 0
            },
            panY: {
                type: Number,
                default: 0
            },
            zoomX: {
                type: Number,
                default: 315
            },
            zoomY: {
                type: Number,
                default: 240
            },
        },
        computed: {
            viewBox: function() {
                return this.panX + ' ' + this.panY + ' ' + this.zoomX + ' ' + this.zoomY;
            }
        }
    }
</script>

<style scoped>

</style>
<template>
    <v-card :color="cardColor">
        <svg xmlns="http://www.w3.org/2000/svg"
             version="1.1"
             :viewBox="viewBoxString">

            <!--        <rect width="315" height="240" rx="5" ry="5"-->
            <!--              style="fill: #c9e5ea; stroke: #E3EFEB; stroke-width: 2"-->
            <!--        ></rect>-->
            <slot>
                <!-- ProvinceVector components slotted here -->
            </slot>
        </svg>
    </v-card>
</template>

<script>
    export default {
        name: "Realm",
        props: {
            viewBox: {
                type: Object,
                default: function() {
                    return {
                        pan_x: 0,
                        pan_y: 0,
                        zoom_x: 315,
                        zoom_y: 240,
                    };
                }
            }

        },
        computed: {
            viewBoxString() {
                return this.viewBox.pan_x + ' ' + this.viewBox.pan_y + ' ' + this.viewBox.zoom_x + ' ' + this.viewBox.zoom_y;
            },
            cardColor() {
                return '#E3EFEB'
            }
        }
    }
</script>

<style scoped>

</style>
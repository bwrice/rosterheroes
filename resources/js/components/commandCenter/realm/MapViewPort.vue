<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-sheet
                id="map-sheet"
                :color="oceanColor"
                @click="click"
                :class="[roundedClass]"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     version="1.1"
                     display="block"
                     :viewBox="viewBoxString"
                >
                    <slot>
                        <!-- Default Slot: ProvinceVector components slotted here -->
                    </slot>
                </svg>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
    import ViewBox from "../../../models/ViewBox";

    export default {
        name: "MapViewPort",
        props: {
            viewBox: {
                type: ViewBox,
                default: function() {
                    return new ViewBox({
                        panX: 0,
                        panY: 0,
                        zoomX: 315,
                        zoomY: 240,
                    });
                }
            },
            oceanColor: {
                type: String,
                default: '#d5f5f5'
            },
            roundedSize: {
                type: String,
                default: 'normal'
            }
        },
        computed: {
            viewBoxString() {
                return this.viewBox.panX + ' ' + this.viewBox.panY + ' ' + this.viewBox.zoomX + ' ' + this.viewBox.zoomY;
            },
            roundedClass() {
                if (this.roundedSize === 'normal') {
                    return 'rounded';
                }
                if (this.roundedSize === 'small') {
                    return 'rounded-sm'
                }
                return '';
            }
        },
        methods: {
            click(e) {
                this.$emit('click', e);
            }
        }
    }
</script>

<style scoped>

</style>

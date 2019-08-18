<template>
    <v-sheet :tile="tile" :color="oceanColor">
        <svg xmlns="http://www.w3.org/2000/svg"
             version="1.1"
             :viewBox="viewBoxString">
            <slot>
                <!-- Default Slot: ProvinceVector components slotted here -->
            </slot>
        </svg>
        <v-overlay :absolute="true" :value="loading">
            <v-progress-circular indeterminate size="48"></v-progress-circular>
        </v-overlay>
    </v-sheet>
</template>

<script>

    import {mapGetters} from 'vuex';

    export default {
        name: "MapViewPort",
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
            },
            oceanColor: {
                type: String,
                default: '#d5f5f5'
            },
            tile: {
                type: Boolean,
                default: true
            },
            requiresRealm: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            ...mapGetters([
                '_realmLoading'
            ]),
            viewBoxString() {
                return this.viewBox.pan_x + ' ' + this.viewBox.pan_y + ' ' + this.viewBox.zoom_x + ' ' + this.viewBox.zoom_y;
            },
            loading() {
                if (this.requiresRealm) {
                    return this._realmLoading;
                }
                return false;
            }

        }
    }
</script>

<style scoped>

</style>

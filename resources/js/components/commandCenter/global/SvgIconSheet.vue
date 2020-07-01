<template>
    <v-sheet
        class="rounded"
        :elevation="elevation"
        :color="color"
        style="margin: 2px"
        @mouseenter.native="showToolTip = true" @mouseleave.native="showToolTip = false"
    >
        <template v-if="toolTipMessage">
            <v-tooltip v-model="showToolTip" bottom>
                <template v-slot:activator="{ on }">
                    <slot>
                        <!-- Default Slot -->
                    </slot>
                </template>
                <span>{{toolTipMessage}}</span>
            </v-tooltip>
        </template>
        <template v-else>
            <slot>
                <!-- Default Slot -->
            </slot>
        </template>
    </v-sheet>
</template>

<script>

    export default {
        name: "SvgIconSheet",
        props: {
            color: {
                type: String,
                default: 'rgba(0, 0, 0, 0.3)'
            },
            elevation: {
                type: Number,
                default: 0
            },
            toolTipMessage: {
                type: String,
                default: ""
            }
        },
        data() {
            return {
                showToolTip: false
            }
        }
    }
</script>

<style scoped>
    .svg-icon {
        margin: 2px;
    }
</style>

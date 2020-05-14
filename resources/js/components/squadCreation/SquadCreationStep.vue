<template>
    <v-stepper-content :step="step" class="pa-1">
        <v-row no-gutters align="center" v-bind:style="stepStyles">
            <v-col cols="12">
                <slot>

                </slot>
            </v-col>
        </v-row>
    </v-stepper-content>
</template>

<script>
    export default {
        name: "SquadCreationStep",
        props: {
            step: {
                type: Number,
                required: true
            }
        },
        computed: {
            stepStyles() {
                return {
                    // 120 px is enough to account for other element heights plus chrome bar at bottom of screens
                    height: window.innerHeight - 120 + 'px'
                }
            }
        },
        methods: {
        }
    }
</script>

<style scoped>

</style>

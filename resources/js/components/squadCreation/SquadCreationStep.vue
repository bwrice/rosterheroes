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
                    height: window.innerHeight - (72 + 24 + 32) + 'px'
                }
            }
        },
        methods: {
        }
    }
</script>

<style scoped>

</style>

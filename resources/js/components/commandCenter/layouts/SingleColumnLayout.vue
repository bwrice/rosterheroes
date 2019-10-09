<template>
    <v-container>
        <template v-if="loading">
            <LoadingOverlay :show-overlay="loading"></LoadingOverlay>
        </template>
        <template v-else>
            <v-row>
                <v-col cols="12" offset-sm="2" sm="8" offset-md="3" md="6" offset-lg="4" lg="4">
                    <slot name="column-one"></slot>
                </v-col>
            </v-row>
        </template>
    </v-container>
</template>

<script>
    import LoadingOverlay from "../global/LoadingOverlay";
    export default {
        name: "SingleColumnLayout",
        components: {LoadingOverlay},
        props: {
            loading: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style scoped>

</style>

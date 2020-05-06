<template>
    <v-col cols="12">
        <v-sheet color="rgba(255,255,255, 0.25)" class="my-2">
            <v-row no-gutters class="pa-2" justify="center" align="center">
                <span class="subtitle-1 font-weight-light">{{notificationText}}</span>
            </v-row>
        </v-sheet>
    </v-col>
</template>

<script>
    export default {
        name: "EmptyNotifier",
        props: {
            notificationText: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

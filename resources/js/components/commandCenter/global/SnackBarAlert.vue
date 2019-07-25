<template>
    <v-snackbar
            v-model="snackbar"
            :color="color"
            :multi-line="true"
            :timeout="timeout"
            :absolute="false"
            :top="true"
    >
        {{ text }}
        <v-btn
                dark
                flat
                @click="snackbar = false"
        >
            Close
        </v-btn>
    </v-snackbar>
</template>

<script>
    export default {
        name: "RhSnackBarAlert",
        data () {
            return {
                snackbar: false,
                color: '',
                mode: '',
                timeout: 2000,
                text: 'Hello, I\'m a snackbar'
            }
        }
    }
</script>

<style scoped>

</style>
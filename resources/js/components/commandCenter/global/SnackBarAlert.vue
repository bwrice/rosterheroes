<template>
    <v-snackbar
        app
        v-model="snackBar"
        :color="this.color"
        :multi-line="true"
        :timeout="this.timeout"
        :absolute="false"
        :top="true"
    >
        {{ this._snackBar.text }}
        <v-btn
                dark
                text
                @click="snackBar = false"
        >
            Close
        </v-btn>
    </v-snackbar>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {

        name: "RhSnackBarAlert",
        data: function() {
            return {
                snackBar: false
            }
        },
        watch: {
            trigger: function(trigger) {
                /**
                 * We don't care about the value of trigger, just know that
                 * it changed. this.snackbar will automatically get set back to
                 * false inside the v-snackbar component
                 */
                this.snackBar = true;
            }
        },
        computed: {
            ...mapGetters([
                '_snackBar'
            ]),
            trigger: function() {
                return this._snackBar.trigger
            },
            color: function() {
                return this._snackBar.color
            },
            timeout: function() {
                return this._snackBar.timeout
            }
        },
    }
</script>

<style scoped>

</style>

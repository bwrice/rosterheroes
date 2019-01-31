<template>
    <div>
        <v-stepper-content step="1">
            <v-flex xs12>
                <v-text-field
                        label="Squad Name"
                        outline
                        @input="updateName"
                        :error="error"
                        error-count="3"
                        :messages="errorMessages"
                ></v-text-field>
            </v-flex>

            <v-btn
                    color="primary"
                    @click="createSquad"
                    :disabled="buttonDisabled"
            >
                Continue
            </v-btn>
        </v-stepper-content>
        <v-snackbar v-model="snackbar" :color="snackbarColor">{{snackbarMessage}}</v-snackbar>
    </div>
</template>

<script>

    export default {
        name: "SquadCreationStepper",

        props: {
            squad: {
                type: Object,
                required: true
            }
        },

        data: function() {
            return {
                name: '',
                snackbar: false,
                snackbarColor: 'error',
                snackbarMessage: '',
                error: false,
                errorMessages: [],
                buttonDisabled: false
            }
        },

        methods: {
            createSquad: function() {
                this.buttonDisabled = true;
                let self = this;
                axios.post('/api/squads', {
                    name: this.name
                }).then(function (response) {
                    self.snackbarColor = 'primary';
                    self.snackbarMessage = 'Success!';
                    self.snackbar = true;
                }).catch(function (error) {
                    self.snackbarColor = 'error';
                    let data = error.response.data;
                    let errorMessages = data.errors[Object.keys(data.errors)[0]];
                    self.snackbarMessage = errorMessages[0];
                    self.errorMessages = errorMessages;
                    self.snackbar = true;
                    self.error = true;
                });
            },
            updateName: function(input) {
                this.name = input;
                this.error = false;
                this.errorMessages = [];
                this.buttonDisabled = false;
            }
        }
    }
</script>

<style scoped>

</style>
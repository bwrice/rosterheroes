<template>
    <div>
        <v-stepper-content step="1">
            <v-flex xs12>
                <v-text-field
                        label="Squad Name"
                        outline
                        v-model="name"
                        @blur="$v.name.$touch()"
                        @input="serverErrors.flush()"
                        :error-messages="nameErrors"
                        messages="Letters, numbers and spaces allowed"
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
    </div>
</template>

<script>

    import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators'
    import Errors from '../../classes/errors'

    export default {
        name: "SquadCreationStepper",

        props: {
            squad: {
                type: Object,
                required: true
            }
        },

        validations: {
            name: {
                required,
                minLength: minLength(4),
                maxLength: maxLength(20),
                format: helpers.regex('format', /^[\w\s]+$/)
            },
        },

        data: function() {
            return {
                name: '',
                serverErrors: new Errors(),
                pendingResponse: false
            }
        },

        methods: {
            createSquad: function() {
                let self = this;
                self.pendingResponse = true;
                axios.post('/api/v1/squads', {
                    name: this.name
                }).then(function (response) {
                    self.$emit('squad-created', response.data);
                    self.pendingResponse = false;
                }).catch(function (error) {
                    self.serverErrors.fill(error.response.data.errors);
                    self.pendingResponse = false;
                });
            }
        },

        computed: {
            nameErrors: function() {
                const errors = [];
                if (!this.$v.name.$dirty) return errors;

                !this.$v.name.required && errors.push('Name is required');
                !this.$v.name.minLength && errors.push('Must be at least 4 characters');
                !this.$v.name.maxLength && errors.push('Cannot be more than 20 characters');
                !this.$v.name.format && errors.push('Only letters, number and spaces allowed');

                if (this.serverErrors.has('name')) {
                    this.serverErrors.get('name').forEach(function(error) {
                        errors.push(error);
                    })
                }
                return errors;
            },
            buttonDisabled: function() {
                return Object.keys(this.serverErrors.errors).length !== 0
                    || this.$v.$invalid
                    || this.pendingResponse;
            }
        }
    }
</script>

<style scoped>

</style>
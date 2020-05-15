<template>
    <SquadCreationStep :step="1">
        <v-row no-gutters>
            <v-text-field
                name="squad-name"
                label="Squad Name"
                outlined
                v-model="name"
                @blur="$v.name.$touch()"
                @input="serverErrors.flush()"
                :error-messages="nameErrors"
                messages="Letters, numbers and spaces allowed"
            ></v-text-field>
        </v-row>
        <v-row no-gutters>
            <v-col cols="10" offset="1" md="8" offset-md="2" lg="4" offset-lg="4" class="mt-6">
                <v-btn
                    block
                    large
                    name="squad-submit"
                    color="primary"
                    @click="createSquad"
                    :disabled="buttonDisabled"
                >
                    Continue
                </v-btn>
            </v-col>
        </v-row>
    </SquadCreationStep>
</template>

<script>

    import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators'
    import Errors from '../../classes/errors'
    import Squad from "../../models/Squad";
    import SquadCreationStep from "./SquadCreationStep";

    export default {
        name: "NameSquadStep",
        components: {SquadCreationStep},
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
                maxLength: maxLength(16),
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
                    let squad = new Squad(response.data.data);
                    self.$emit('squad-created', squad);
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
                !this.$v.name.maxLength && errors.push('Cannot be more than 16 characters');
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

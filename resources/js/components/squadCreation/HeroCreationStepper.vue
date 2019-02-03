<template>
    <div>
        <v-stepper-content step="2">
            <v-container>
                <v-layout row wrap justify-center>
                    <v-flex xs12 md8>
                        <p class="text-xs-center title">
                            <slot></slot>
                        </p>
                        <p class="body-1">
                            Choose a hero class and a hero race. Then give you hero a name.
                            You must create a hero for each hero race (you will create four in total),
                            and you are required to have at least
                            one hero of each class.
                        </p>
                    </v-flex>
                </v-layout>
                <v-layout row wrap justify-center align-center>
                    <v-flex xs12 md8>
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-radio-group
                                        v-model="heroClass"
                                        label="Hero Class"
                                        @blur="$v.heroClass.$touch()"
                                        @input="serverErrors.flush()"
                                        :error-messages="classErrors"
                                        column>
                                    <v-radio label="Warrior" value="warrior"></v-radio>
                                    <v-radio label="Ranger" value="ranger"></v-radio>
                                    <v-radio label="Sorcerer" value="sorcerer"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs6>
                                <v-radio-group
                                        v-model="heroRace"
                                        label="Hero Race"
                                        @blur="$v.heroRace.$touch()"
                                        @input="serverErrors.flush()"
                                        :error-messages="raceErrors"
                                        column>
                                    <v-radio label="Human" value="human"></v-radio>
                                    <v-radio label="Elf" value="elf"></v-radio>
                                    <v-radio label="Dwarf" value="dwarf"></v-radio>
                                    <v-radio label="Orc" value="orc"></v-radio>
                                </v-radio-group>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-text-field
                                label="Hero Name"
                                outline
                                v-model="name"
                                @blur="$v.name.$touch()"
                                @input="serverErrors.flush()"
                                :error-messages="nameErrors"
                                messages="Letters, numbers and spaces allowed"
                        ></v-text-field>
                        <v-btn
                                color="primary"
                                @click="createHero"
                                :disabled="$v.$invalid"
                        >
                            Continue
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-stepper-content>
        <!--<v-snackbar v-model="snackbar" :color="snackbarColor">{{snackbarMessage}}</v-snackbar>-->
    </div>
</template>

<script>

    import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators'
    import Errors from '../../classes/errors'

    export default {
        name: "HeroCreationStepper",

        props: {
            heroes: {
                required: true
            }
        },

        validations: {
            name: {
                required,
                minLength: minLength(4),
                maxLength: maxLength(20),
                format: helpers.regex('format', /^[\w\-\s]+$/)
            },
            heroClass: { required },
            heroRace: { required }
        },

        data () {
            return {
                heroClass: null,
                heroRace: null,
                name: '',
                buttonDisabled: false,
                serverErrors: new Errors()
            }
        },

        methods: {
            createHero: function() {

            },
            clearNameErrors: function() {
                this.nameErrors = [];
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
                return errors
            },
            raceErrors: function() {
                const errors = [];
                if (this.serverErrors.has('race')) {
                    this.serverErrors.get('race').forEach(function(error) {
                        errors.push(error);
                    })
                }
                return errors;
            },
            classErrors: function() {
                const errors = [];
                if (this.serverErrors.has('class')) {
                    this.serverErrors.get('class').forEach(function(error) {
                        errors.push(error);
                    })
                }
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>
<template>
    <v-stepper-content :step="heroStep.step">
        <v-layout wrap justify-center>
            <v-flex xs12 md8>
                <p class="text-xs-center title">
                    {{heroStep.title}}
                </p>
                <p class="body-1">
                    Choose a hero class and a hero race. Then give you hero a name.
                    You must create a hero for each hero race (you will create four in total),
                    and you are required to have at least
                    one hero of each class.
                </p>
            </v-flex>
        </v-layout>
        <v-layout wrap justify-center align-center>
            <v-flex xs12 md8>
                <v-layout wrap>
                    <v-flex xs6>
                        <v-radio-group
                                v-model="heroClass"
                                label="Hero Class"
                                @blur="$v.heroClass.$touch()"
                                @click="serverErrors.flush()"
                                :error-messages="classErrors"
                                column>
                            <v-radio :disabled="! validClass('warrior')" label="Warrior" value="warrior"></v-radio>
                            <v-radio :disabled="! validClass('ranger')" label="Ranger" value="ranger"></v-radio>
                            <v-radio :disabled="! validClass('sorcerer')" label="Sorcerer" value="sorcerer"></v-radio>
                        </v-radio-group>
                    </v-flex>
                    <v-flex xs6>
                        <v-radio-group
                                v-model="heroRace"
                                label="Hero Race"
                                @blur="$v.heroRace.$touch()"
                                @click="serverErrors.flush()"
                                :error-messages="raceErrors"
                                column>
                            <v-radio :disabled="! validRace('human')" label="Human" value="human">
                            </v-radio>
                            <v-radio :disabled="! validRace('elf')" label="Elf" value="elf"></v-radio>
                            <v-radio :disabled="! validRace('dwarf')" label="Dwarf" value="dwarf"></v-radio>
                            <v-radio :disabled="! validRace('orc')" label="Orc" value="orc"></v-radio>
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
                        :disabled="$v.$invalid || buttonDisabled"
                >
                    Continue
                </v-btn>
            </v-flex>
        </v-layout>
    </v-stepper-content>
</template>

<script>

    import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators'
    import Errors from '../../classes/errors'

    export default {
        name: "HeroCreationStepper",

        props: {
            heroStep: {
                type: Object,
                required: true
            },
            squadUuid: {
                required: true
            },
            allowedHeroClasses: {
                type: Array,
                required: true
            },
            allowedHeroRaces: {
                type: Array,
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
                let self = this;
                self.buttonDisabled = true;
                axios.post('/api/v1/squad/' + this.squadUuid + '/heroes', {
                    name: this.name,
                    race: this.heroRace,
                    class: this.heroClass
                }).then(function (response) {
                    self.buttonDisabled = false;
                    self.$emit('hero-created', response.data, self.heroStep.step)
                }).catch(function (error) {
                    self.buttonDisabled = false;
                    self.serverErrors.fill(error.response.data.errors);
                });
            },
            validClass: function(heroClass) {
                let allowed = false;
                this.allowedHeroClasses.forEach(function(allowedClass) {
                    if(allowedClass.name === heroClass) {
                        allowed = true;
                    }
                });
                return allowed;
            },
            validRace: function(heroRace) {
                let allowed = false;
                this.allowedHeroRaces.forEach(function(allowedRace) {
                    if(allowedRace.name === heroRace) {
                        allowed = true;
                    }
                });
                return allowed;
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
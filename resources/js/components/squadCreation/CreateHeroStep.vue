<template>
    <SquadCreationStep :step="heroStep.step">
        <v-row no-gutters justify="center">
            <span class="title font-weight-thin my-1">{{titleText}}</span>
        </v-row>
        <v-row no-gutters>
            <v-col cols="6" class="px-2">
                <v-row no-gutters align="center" class="flex-column">
                    <span class="subtitle-1 font-weight-bold" style="color: rgba(255, 255, 255, 0.8)">Hero Race</span>
                    <v-radio-group
                        :name="'hero-race-' + heroNumber"
                        v-model="heroRace"
                        @blur="$v.heroRace.$touch()"
                        @click="serverErrors.flush()"
                        :error-messages="raceErrors"
                        column
                        class="mt-0"
                    >
                        <v-radio
                            :disabled="! validRace('human')"
                            label="Human"
                            value="human">
                        </v-radio>
                        <v-radio
                            :disabled="! validRace('elf')"
                            label="Elf"
                            value="elf">
                        </v-radio>
                        <v-radio
                            :disabled="! validRace('dwarf')"
                            label="Dwarf"
                            value="dwarf">
                        </v-radio>
                        <v-radio
                            :disabled="! validRace('orc')"
                            label="Orc"
                            value="orc">
                        </v-radio>
                    </v-radio-group>
                </v-row>
            </v-col>
            <v-col cols="6" class="px-2">
                <v-row no-gutters align="center" class="flex-column">
                    <span class="subtitle-1 font-weight-bold" style="color: rgba(255, 255, 255, 0.8)">Hero Class</span>
                    <v-radio-group
                        :name="'hero-class-' + heroNumber"
                        v-model="heroClass"
                        @blur="$v.heroClass.$touch()"
                        @click="serverErrors.flush()"
                        :error-messages="classErrors"
                        column
                        class="mt-0"
                    >
                        <v-radio
                            :disabled="! validClass('warrior')"
                            label="Warrior"
                            value="warrior">
                        </v-radio>
                        <v-radio
                            :disabled="! validClass('ranger')"
                            label="Ranger"
                            value="ranger">
                        </v-radio>
                        <v-radio
                            :disabled="! validClass('sorcerer')"
                            label="Sorcerer"
                            value="sorcerer">
                        </v-radio>
                    </v-radio-group>
                </v-row>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="12" class="mt-1">
                <v-text-field
                    label="Hero Name"
                    :name="'hero-name-' + heroNumber"
                    outlined
                    v-model="name"
                    @blur="$v.name.$touch()"
                    @input="serverErrors.flush()"
                    :error-messages="nameErrors"
                    messages="Letters, numbers and spaces allowed"
                ></v-text-field>
            </v-col>
            <v-col cols="10" offset="1" md="8" offset-md="2" lg="4" offset-lg="4" class="mt-2">
                <v-btn
                    block
                    large
                    :name="'hero-submit-' + heroNumber"
                    color="primary"
                    @click="createHero"
                    :disabled="$v.$invalid || buttonDisabled"
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
    import SquadCreationStep from "./SquadCreationStep";

    export default {
        name: "CreateHeroStep",
        components: {SquadCreationStep},
        props: {
            heroStep: {
                type: Object,
                required: true
            },
            squadSlug: {
                type: String,
                default: ''
            },
            allowedHeroClasses: {
                type: Array,
                required: true
            },
            allowedHeroRaces: {
                type: Array,
                required: true
            },
            heroRaces: {
                type: Array,
                required: true
            },
            heroClasses: {
                type: Array,
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
                axios.post('/api/v1/squad/' + this.squadSlug + '/heroes', {
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
            },
            heroNumber() {
                return this.heroStep.step - 1;
            },
            titleText() {
                let positionText = '';
                switch(this.heroNumber) {
                    case 1:
                        positionText = 'First';
                        break;
                    case 2:
                        positionText = 'Second';
                        break;
                    case 3:
                        positionText = 'Third';
                        break;
                    case 4:
                        positionText = 'Final';
                        break;
                }
                return "Create " + positionText + " Hero";
            }
        }
    }
</script>

<style scoped>

</style>

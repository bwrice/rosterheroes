<template>
    <v-app dark>
        <v-content style="background-image: linear-gradient(#236161, #2f3838);">
            <v-container>
                <v-row align="center" no-gutters style="height: 500px">
                    <v-col cols="12" offset-md="2" md="8" offset-lg="3" lg="6">
                        <div class="flex align-center">
                            <v-stepper v-model="progress" style="background: none; box-shadow: none">
                                <v-stepper-header style="box-shadow:none">
                                    <v-stepper-step :complete="progress > 1" step="1"></v-stepper-step>

                                    <v-divider></v-divider>
                                    <v-stepper-step :complete="progress > 2" step="2"></v-stepper-step>

                                    <v-divider></v-divider>

                                    <v-stepper-step :complete="progress > 3" step="3"></v-stepper-step>

                                    <v-divider></v-divider>

                                    <v-stepper-step :complete="progress > 4" step="4"></v-stepper-step>

                                    <v-divider></v-divider>

                                    <v-stepper-step :complete="progress > 5" step="5"></v-stepper-step>
                                </v-stepper-header>

                                <v-stepper-items>

                                    <SquadNameStepper :squad="squadClone"
                                                          @squad-created="handleSquadNameCreated"></SquadNameStepper>

                                    <HeroCreationStepper
                                        v-for="heroStep in heroSteps"
                                        :heroStep="heroStep"
                                        :key="heroStep.id"
                                        :squad-slug="squadClone.slug"
                                        :allowed-hero-classes="allowedHeroClasses"
                                        :allowed-hero-races="allowedHeroRaces"
                                        @hero-created="handleHeroCreated"
                                    >
                                        Create Your First Hero
                                    </HeroCreationStepper>

                                    <v-stepper-content :step="6">
                                        <p>Congrats!!! Your squad,<br>
                                            <span class="headline text-xs-center">{{ squadClone.name }}</span>
                                        <p>is all set up. You can now head over to the <br>
                                            command center to begin your journey
                                        </p>
                                        <v-btn :href="'/command-center/' + this.squadClone.slug" color="primary">
                                            Go to Command Center
                                        </v-btn>
                                    </v-stepper-content>

                                </v-stepper-items>
                            </v-stepper>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>

    import SquadNameStepper from '../components/squadCreation/SquadNameStepper'
    import HeroCreationStepper from '../components/squadCreation/HeroCreationStepper'

    export default {

        props: {
            squad: {
                type: Object,
                default: function() {
                    return {
                    }
                }
            },
            heroes: {
                default: function() {
                    return [];
                }
            },
            heroClasses: {
                default: function() {
                    return [];
                }
            },
            heroRaces: {
                default: function() {
                    return [];
                }
            }
        },

        created: function() {
            this.squadClone = _.cloneDeep(this.squad);
            this.heroesClone = _.cloneDeep(this.heroes);
            this.allowedHeroClasses = _.cloneDeep(this.heroClasses);
            this.allowedHeroRaces = _.cloneDeep(this.heroRaces);
        },

        data () {
            return {
                squadClone: {},
                heroesClone: [],
                allowedHeroClasses: [],
                allowedHeroRaces: [],
                squadCreated: false,
                // progress: 1,
                heroSteps: [
                    { hero: 1, title: "Create Your First Hero", step: 2 },
                    { hero: 2, title: "Create Your Second Hero", step: 3 },
                    { hero: 3, title: "Create Your Third Hero", step: 4 },
                    { hero: 4, title: "Create Your Last Hero", step: 5 }
                ]
            }
        },

        components: {
            SquadNameStepper,
            HeroCreationStepper
        },

        methods: {
            handleSquadNameCreated: function(squad) {
                this.squadClone = squad;
                this.squadCreated = true;
                this.updateDependencies();
                // this.updateProgress();
            },
            updateHeroClasses: function() {
                let self = this;
                axios.get('/api/v1/squad/' + this.squadClone.slug + '/hero-classes').
                then(function (response) {
                    self.allowedHeroClasses = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            updateHeroRaces: function() {
                let self = this;
                axios.get('/api/v1/squad/' + this.squadClone.slug + '/hero-races').
                then(function (response) {
                    self.allowedHeroRaces = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            handleHeroCreated: function(hero, step) {
                this.heroesClone.push(hero);
                if (step < 5) {
                    this.updateDependencies();
                }
            },
            updateDependencies: function() {
                this.updateHeroClasses();
                this.updateHeroRaces();
            }
        },
        computed: {
            progress: {
                get () {
                    if(this.squadClone.name === undefined) {
                        return 1;
                    } else {
                        return 2 + this.heroesClone.length;
                    }
                },
                set (value) {

                }
            }
        }
    }

</script>

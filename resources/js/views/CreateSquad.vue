<template>
    <v-app dark>
        <v-content style="background-image: linear-gradient(#236161, #2f3838); overflow-y: hidden">
            <v-container>
                <v-row align="center" no-gutters>
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

                                    <NameSquadStep :squad="squadClone"
                                                          @squad-created="handleSquadNameCreated"></NameSquadStep>

                                    <CreateHeroStep
                                        v-for="heroStep in heroSteps"
                                        :heroStep="heroStep"
                                        :key="heroStep.id"
                                        :squad-slug="squadClone.slug"
                                        :allowed-hero-classes="allowedHeroClasses"
                                        :allowed-hero-races="allowedHeroRaces"
                                        :hero-classes="heroClasses"
                                        :hero-races="heroRaces"
                                        @hero-created="handleHeroCreated"
                                    >
                                        Create Your First Hero
                                    </CreateHeroStep>

                                    <v-stepper-content :step="6">
                                        <v-row no-gutters class="flex-column pt-6" align="center" justify="center">
                                            <span class="subtitle-2" style="color: rgba(255, 255, 255, .85)">
                                                Congrats!!!, Your squad,
                                            </span>
                                            <span class="title font-weight-bold py-4">
                                                {{ squadClone.name }}
                                            </span>
                                            <span class="subtitle-2 px-4 text-center" style="color: rgba(255, 255, 255, .85)">
                                                is all set up. You can now head over to the
                                                command center to begin your journey.
                                            </span>
                                            <v-btn
                                                :href="'/command-center/' + this.squadClone.slug"
                                                color="primary"
                                                class="mt-6"
                                            >
                                                Go to Command Center
                                            </v-btn>
                                        </v-row>
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

    import NameSquadStep from '../components/squadCreation/NameSquadStep'
    import CreateHeroStep from '../components/squadCreation/CreateHeroStep'

    import * as referenceApi from '../api/referenceApi';
    import HeroRace from "../models/HeroRace";
    import HeroClass from "../models/HeroClass";

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
            allowedHeroClassesProp: {
                default: function() {
                    return [];
                }
            },
            allowedHeroRacesProp: {
                default: function() {
                    return [];
                }
            }
        },

        created: function() {
            this.squadClone = _.cloneDeep(this.squad);
            this.heroesClone = _.cloneDeep(this.heroes);
            this.allowedHeroClasses = _.cloneDeep(this.allowedHeroClassesProp);
            this.allowedHeroRaces = _.cloneDeep(this.allowedHeroRacesProp);
            this.setHeroClasses();
            this.setHeroRaces();
        },

        data () {
            return {
                squadClone: {},
                heroesClone: [],
                heroClasses: [],
                heroRaces: [],
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
            NameSquadStep,
            CreateHeroStep
        },

        methods: {
            handleSquadNameCreated: function(squad) {
                this.squadClone = squad;
                this.squadCreated = true;
                this.updateAllowedHeroClasses();
                this.updateAllowedHeroRaces();
            },
            updateAllowedHeroClasses: function() {
                let self = this;
                axios.get('/api/v1/squad/' + this.squadClone.slug + '/hero-classes').
                then(function (response) {
                    self.allowedHeroClasses = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            updateAllowedHeroRaces: function() {
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
                    this.updateAllowedHeroClasses();
                    this.updateAllowedHeroRaces();
                }
            },
            async setHeroRaces() {
                try {
                    let response = await referenceApi.getHeroRaces();
                    this.heroRaces = response.data.map(function (heroRaceResponse) {
                        return new HeroRace(heroRaceResponse);
                    });
                } catch (e) {
                    console.log(e);
                }
            },
            async setHeroClasses() {
                try {
                    let response = await referenceApi.getHeroClasses();
                    this.heroClasses = response.data.map(function (heroClassResponse) {
                        return new HeroClass(heroClassResponse)
                    });
                } catch (e) {
                    console.log(e);
                }
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

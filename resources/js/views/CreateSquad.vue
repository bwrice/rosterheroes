<template>
    <v-app dark>
        <v-toolbar>
            <v-toolbar-title>Create Your Squad</v-toolbar-title>
        </v-toolbar>
        <v-container
                :class="{
                'pa-0': $vuetify.breakpoint.smAndDown,
                'pa-5': $vuetify.breakpoint.mdAndUp
                }"
        >
            <v-layout row>
                <v-flex offset-md-1 offset-lg-2>
                    <v-card height="150px"></v-card>
                    <v-stepper v-model="e1">
                        <v-stepper-header>
                            <v-stepper-step :complete="squadCreated()" step="1">Squad Name</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="e1 > 2" step="2">Hero One</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="e1 > 3" step="3">Hero Two</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step :complete="e1 > 4" step="4">Hero Three</v-stepper-step>

                            <v-divider></v-divider>

                            <v-stepper-step step="5">Hero Four</v-stepper-step>
                        </v-stepper-header>

                        <v-stepper-items>

                            <SquadCreationStepper :squad="squad"></SquadCreationStepper>

                            <v-stepper-content step="2">
                                <v-card
                                        class="mb-5"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                        @click="e1 = 3"
                                >
                                    Continue
                                </v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <v-card
                                        class="mb-5"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                        @click="e1 = 4"
                                >
                                    Continue
                                </v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="4">
                                <v-card
                                        class="mb-5"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                        @click="e1 = 5"
                                >
                                    Continue
                                </v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="5">
                                <v-card
                                        class="mb-5"
                                        color="grey lighten-1"
                                        height="200px"
                                ></v-card>

                                <v-btn
                                        color="primary"
                                        @click="e1 = 1"
                                >
                                    Finish
                                </v-btn>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>

    import AxeIcon from '../components/icons/AxeIcon'
    import BowIcon from '../components/icons/BowIcon'
    import WandIcon from '../components/icons/WandIcon'
    import SquadCreationStepper from '../components/squadCreation/SquadCreationStepper'

    export default {

        props: {
            squad: {
                type: Object,
                default: function() {
                    return {}
                }
            }
        },

        data () {
            return {
                e1: 0
            }
        },

        components: {
            AxeIcon,
            BowIcon,
            WandIcon,
            SquadCreationStepper
        },

        methods: {
            squadCreated: function() {
                return this.squad.name !== undefined;
            }
        }
    }

</script>
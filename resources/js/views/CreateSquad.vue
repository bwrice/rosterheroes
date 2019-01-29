<template>
    <v-app dark>
        <h2 class="text-xs-center mt-2">Create Your Squad</h2>
        <v-stepper v-model="e1">
            <v-stepper-header>
                <v-stepper-step :complete="e1 > 1" step="1">Squad Name</v-stepper-step>

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

                <v-stepper-content step="1">
                    <v-card
                            class="mb-5"
                            color="grey lighten-1"
                            height="200px"
                    ><div>
                        <WandIcon></WandIcon>
                    </div>
                    </v-card>

                    <v-btn
                            color="primary"
                            @click="e1 = 2"
                    >
                        Continue
                    </v-btn>
                </v-stepper-content>

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
    </v-app>
</template>

<script>

    import AxeIcon from '../components/icons/AxeIcon'
    import BowIcon from '../components/icons/BowIcon'
    import WandIcon from '../components/icons/WandIcon'

    export default {
        data () {
            return {
                e1: 0
            }
        },
        components: {
            AxeIcon,
            BowIcon,
            WandIcon
        }
    }

</script>
<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-row no-gutters align="center" justify="center" :class="[textSizeClass, fontWeightClass]">
                   Nations: Coming Soon!
                </v-row>
            </v-col>
            <v-col offset-sm="2" sm="8" offset-md="3" md="6" offset-lg="4" lg="4">
                <v-row no-gutters class="mx-3">
                    <ul>
                        <li>Join or create a nation</li>
                        <li>Rule over provinces
                            <ul>
                                <li>Purchase unowned provinces</li>
                                <li>Invade competing nations' provinces</li>
                                <li>Defend your provinces from invaders</li>
                                <li>Start an uprising against larger nations</li>
                            </ul>
                        </li>
                        <li>Grow your nation's economy
                            <ul>
                                <li>Build markets and shops</li>
                                <li>Tax squads traveling through your lands</li>
                                <li>Pillage enemy nations</li>
                            </ul>
                        </li>
                        <li>Become a member of your nation's council
                            <ul>
                                <li>Earn council seats</li>
                                <li>Vote on war strategies</li>
                                <li>Banish dissenters and spies</li>
                            </ul>
                        </li>
                    </ul>
                </v-row>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "NationMain",
        computed: {
            textSizeClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'headline';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'display-2'
                }
            },
            fontWeightClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'font-weight-bold';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'font-weight-black'
                }
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-menu
        bottom
        left
        v-model="opened"
    >
        <template v-slot:activator="{ on }">
            <v-btn
                :color="iconColor"
                icon
                v-on="on"
            >
                <v-icon>schedule</v-icon>
            </v-btn>
        </template>
        <v-list color="#778f85">
            <v-list-item>
                <v-spacer></v-spacer>
                <v-icon @click="opened = false" color="rgba(0,0,0,.7)">close</v-icon>
            </v-list-item>
            <template v-if="secondsRemaining > 0">
                <v-list-item>
                    <v-row no="gutters" justify="center">
                    <span
                        class="font-weight-bold"
                        :class="[$vuetify.breakpoint.name === 'xs' ? 'subtitle-1' : 'headline' ]"
                        style="color: rgba(0,0,0,.7)"
                    >WEEK LOCKS IN</span>
                    </v-row>
                </v-list-item>
                <v-list-item>
                    <div :style="countdownDivStyles">
                        <FlipCountdown :deadline="deadline"></FlipCountdown>
                    </div>
                </v-list-item>
            </template>
            <template v-else>
                <v-list-item>
                    <v-row no="gutters" justify="center">
                    <span
                        class="font-weight-bold"
                        :class="[$vuetify.breakpoint.name === 'xs' ? 'subtitle-1' : 'headline' ]"
                        style="color: rgba(0,0,0,.7)"
                    >WEEK LOCKED</span>
                    </v-row>
                </v-list-item>
                <v-list-item>
                    <v-row no="gutters" justify="center" class="pa-3">
                    <p
                        :class="[$vuetify.breakpoint.name === 'xs' ? 'body-2' : 'body-1' ]"
                        style="color: rgba(0,0,0,.7)"
                    >Campaigns are being processed. <br> Tomorrow a new week starts!</p>
                    </v-row>
                </v-list-item>
            </template>
        </v-list>
    </v-menu>
</template>

<script>

    import {mapGetters} from 'vuex';

    import FlipCountdown from 'vue2-flip-countdown';

    export default {
        name: "NavBarWeekInfo",
        components: {
            FlipCountdown
        },
        data() {
            return {
                opened: false
            }
        },
        computed: {
            ...mapGetters([
                '_currentWeek'
            ]),
            iconColor() {
                if (this.daysRemaining > 1) {
                    return 'primary';
                }
                if (this.daysRemaining > 0) {
                    return 'success';
                }
                if (this.hoursRemaining > 3) {
                    return 'accent';
                }
                if (this._currentWeek.secondsUntilAdventuringLocks > 0) {
                    return 'error';
                }
                return '#fc00c5';
            },
            deadline() {
                if (this._currentWeek.adventuringLocksAt) {
                    return this._currentWeek.adventuringLocksAt.format('YYYY-MM-DD hh:mm:ss');
                }
                return moment().format('YYYY-MM-DD hh:mm:ss');
            },
            countdownDivStyles() {
                let styles = {
                    'color': "rgba(0,0,0,.85)"
                };
                if (this.$vuetify.breakpoint.name === 'xs') {
                    styles['max-width'] = '200px';
                }
                return styles;
            },
            diff() {
                if (this._currentWeek.adventuringLocksAt) {
                    return this._currentWeek.adventuringLocksAt - Date.now();
                }
                return 0;
            },
            daysRemaining() {
                return Math.trunc(this._currentWeek.secondsUntilAdventuringLocks/(60 * 60 * 24));
            },
            hoursRemaining() {
                return Math.trunc(this._currentWeek.secondsUntilAdventuringLocks/(60 * 60));
            },
            secondsRemaining() {
                return this._currentWeek.secondsUntilAdventuringLocks;
            }
        }
    }
</script>

<style scoped>

</style>

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

        <v-list>
            <v-list-item>
                <v-spacer></v-spacer>
                <v-icon @click="opened = false">close</v-icon>
            </v-list-item>
            <v-list-item>
                {{countdown}}
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>

    import {mapGetters} from 'vuex';

    export default {
        name: "NavBarWeekInfo",
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
                return 'success';
            },
            timeUntilWeekLocks() {
                let totalSeconds = this._currentWeek.secondsUntilAdventuringLocks;
                if (totalSeconds > 0) {
                    let secondsInDays = 60 * 60 * 24;
                    let days = Math.floor(totalSeconds/secondsInDays);
                    totalSeconds -=  (days * secondsInDays);
                    let secondsInHours = 60 * 60;
                    let hours = Math.floor(totalSeconds/secondsInHours);
                    totalSeconds -=  (hours * secondsInHours);
                    let secondsInMinutes = 60;
                    let minutes = Math.floor(totalSeconds/secondsInMinutes);
                    totalSeconds -= (secondsInMinutes * minutes);
                    let seconds = totalSeconds;
                    return {
                        days,
                        hours,
                        minutes,
                        seconds
                    }
                }
                return null;
            },
            countdown() {
                if (! this.timeUntilWeekLocks) {
                    return "Week Locked";
                }
                let countdown = this.timeUntilWeekLocks.days + 'd ';
                countdown += this.timeUntilWeekLocks.hours + 'h ';
                countdown += this.timeUntilWeekLocks.minutes + 'm ';
                countdown += this.timeUntilWeekLocks.seconds + 's';
                return countdown;
            }
        }
    }
</script>

<style scoped>

</style>

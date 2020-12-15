<template>
    <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
            <v-progress-circular
                :rotate="90"
                :size="66"
                :width="10"
                :value="progressValue"
                :color="color"
                v-bind="attrs"
                v-on="on"
            >
                {{fantasyPower}}
            </v-progress-circular>
        </template>
        <span>Fantasy Power</span>
    </v-tooltip>
</template>

<script>
    export default {
        name: "FantasyPowerIndicator",
        props: {
            fantasyPower: {
                type: Number,
                required: true
            }
        },
        computed: {
            progressValue() {
                return 10 * Math.sqrt(this.fantasyPower);
            },
            color() {
                if (this.progressValue > 80) {
                    return 'purple';
                }
                if (this.progressValue > 60) {
                    return 'pink';
                }
                if (this.progressValue > 40) {
                    return 'orange';
                }
                if (this.progressValue > 20) {
                    return 'yellow';
                }
                return 'green';
            }
        }
    }
</script>

<style scoped>

</style>

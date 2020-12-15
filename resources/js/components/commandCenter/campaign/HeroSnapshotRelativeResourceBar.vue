<template>
    <v-progress-linear
        :color="color"
        :height="20"
        :value="progressBarValue"
    >
        {{amount}}
    </v-progress-linear>
</template>

<script>
    import {mapGetters} from 'vuex';
    import HeroSnapshot from "../../../models/HeroSnapshot";

    export default {
        name: "HeroSnapshotRelativeResourceBar",
        props: {
            heroSnapshot: {
                type: HeroSnapshot,
                required: true
            },
            resourceName: {
                type: String,
                required: true
            },
            color: {
                type: String,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_squadSnapshot'
            ]),
            progressBarValue() {
                if (! this._squadSnapshot) {
                    return 100;
                }
                let self = this;
                let amounts = this._squadSnapshot.heroSnapshots.map(function (heroSnapshot) {
                    return heroSnapshot[self.resourceName];
                });
                let maxValue = amounts.reduce(function(amountA, amountB) {
                    return Math.max(amountA, amountB);
                }, 100);
                return 100 * this.heroSnapshot[self.resourceName]/maxValue;
            },
            amount() {
                return this.heroSnapshot[this.resourceName];
            }
        }
    }
</script>

<style scoped>

</style>

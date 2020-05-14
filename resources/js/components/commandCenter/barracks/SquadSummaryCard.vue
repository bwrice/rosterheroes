<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">REPORT: {{_squad.name}}</span>
        </v-col>
        <v-col cols="12">
            <v-sheet
                color="#4c7c7d"
                class="my-1"
            >
                <v-row no-gutters align="center">
                    <v-col cols="4">
                        <v-row no-gutters class="pa-2">
                            <v-col cols="12">
                                <LevelIcon :level="_squad.level"></LevelIcon>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="8">
                        <v-row no-gutters class="pa-2">
                            <v-col cols="12">
                                <v-sheet
                                    tile
                                    v-for="(stat, name) in squadStats"
                                    :key="name"
                                    color="rgba(0,0,0,.4)"
                                    class="px-2"
                                    style="margin-bottom: 2px">
                                    <v-row no-gutters justify="space-between">
                                        <span class="font-weight-regular" :class="[textSizeClass]" style="color: rgba(255,255,255,0.8)">{{stat.name}}:</span>
                                        <span class="font-weight-regular" :class="[textSizeClass]" style="color: rgba(255,255,255,0.8)">{{stat.value}}</span>
                                    </v-row>
                                </v-sheet>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row no-gutters class="pb-2 px-2">
                    <v-col cols="12">
                        <v-progress-linear
                            color="#3d4a6e"
                            height="16"
                            :value="experienceProgressValue"
                        >
                            <template v-slot="{ value }">
                                <span class="caption font-weight-bold">{{ experienceText }}</span>
                            </template>
                        </v-progress-linear>
                    </v-col>
                </v-row>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
    import {mapGetters} from 'vuex';
    import LevelIcon from "../../icons/LevelIcon";
    import GradientBar from "../global/GradientBar";
    export default {
        name: "SquadSummaryCard",
        components: {GradientBar, LevelIcon},
        computed: {
            ...mapGetters([
                '_squad',
                '_currentLocationProvince'
            ]),
            textSizeClass() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'sub-title';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'title'
                }
            },
            squadStats() {
                return [
                    {
                        name: 'Gold',
                        value: this._squad.gold.toLocaleString()
                    },
                    {
                        name: 'Experience',
                        value: this._squad.experience.toLocaleString()
                    },
                    {
                        name: 'Spirit Essence',
                        value: this._squad.spiritEssence.toLocaleString()
                    },
                    {
                        name: 'Location',
                        value: this._currentLocationProvince.name
                    },
                ]
            },
            experienceProgressValue() {
                let expOverLevel = this._squad.experienceOverLevel;
                return Math.ceil(100 * expOverLevel/this.totalLevelExperience)
            },
            experienceText() {
                let expOverLevel = this._squad.experienceOverLevel;
                return expOverLevel + '/' + this.totalLevelExperience;
            },
            totalLevelExperience() {
                return this._squad.experienceOverLevel + this._squad.experienceUntilNextLevel;
            }
        }
    }
</script>

<style scoped>

</style>

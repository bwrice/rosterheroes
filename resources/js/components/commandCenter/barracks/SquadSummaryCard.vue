<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">REPORT: {{_squad.name}}</span>
        </v-col>
        <v-col cols="12">
            <v-sheet
                color="#5c707d"
                class="my-1"
            >
                <v-row no-gutters align="center">
                    <v-col cols="4" md="3">
                        <v-row no-gutters class="pa-2">
                            <v-col cols="12">
                                <LevelIcon :level="_squad.level"></LevelIcon>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="8" md="9" class="pa-2">
                        <StatRow
                            v-for="(stat, name) in squadStats"
                            :key="name"
                            :name="stat.name"
                            :value="stat.value"
                        ></StatRow>
                    </v-col>
                </v-row>
                <v-row no-gutters class="pb-2 px-2">
                    <v-col cols="12">
                        <v-progress-linear
                            color="#3d4a6e"
                            :height="experienceProgressHeight"
                            :value="experienceProgressValue"
                        >
                            <template v-slot="{ value }">
                                <span class="font-weight-bold" :class="[experienceProgressTextSize]">{{ experienceText }}</span>
                            </template>
                        </v-progress-linear>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="6">
                        <HeroesReadinessPanel></HeroesReadinessPanel>
                    </v-col>
                    <v-col cols="6">
                        <CampaignReadinessPanel></CampaignReadinessPanel>
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
    import CampaignReadinessPanel from "./CampaignReadinessPanel";
    import HeroesReadinessPanel from "./HeroesReadinessPanel";
    import StatRow from "../global/StatRow";
    export default {
        name: "SquadSummaryCard",
        components: {StatRow, HeroesReadinessPanel, CampaignReadinessPanel, GradientBar, LevelIcon},
        computed: {
            ...mapGetters([
                '_squad',
                '_currentLocationProvince'
            ]),
            experienceProgressHeight() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 16;
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 24
                }
            },
            experienceProgressTextSize() {
                switch (this.$vuetify.breakpoint.name) {
                    case 'xs':
                    case 'sm':
                        return 'caption';
                    case 'md':
                    case 'lg':
                    case 'xl':
                        return 'body-2'
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
                        name: 'Favor',
                        value: this._squad.favor.toLocaleString()
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

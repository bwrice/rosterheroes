<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">STAT BONUSES</span>
        </v-col>
        <v-col cols="12">
            <v-card
                color="#32343d"
            >
                <v-tabs
                    v-model="tab"
                    mobile-breakpoint="10"
                    centered
                    background-color="#32343d"
                    color="#b3c9c3"
                    slider-color="primary"
                >
                    <v-tab
                        v-for="(bonusGroup, sport) in statBonusGroups"
                        :key="sport"
                    >
                        {{ bonusGroup.sport.name }}
                    </v-tab>
                </v-tabs>
                <v-tabs-items v-model="tab" style="background-color: transparent">
                    <StatBonusGroupTabItem
                        v-for="(bonusGroup, sport) in statBonusGroups"
                        :key="sport"
                        :sport="bonusGroup.sport"
                        :stat-measurable-bonuses="bonusGroup.statMeasurableBonuses"
                    ></StatBonusGroupTabItem>
                </v-tabs-items>
            </v-card>
        </v-col>
        <v-sheet>
        </v-sheet>
    </v-row>
</template>

<script>
    import Hero from "../../../models/Hero";
    import {mapGetters} from 'vuex';
    import StatBonusGroupTabItem from "./StatBonusGroupTabItem";

    export default {
        name: "HeroStatMeasurableBonusesCard",
        components: {StatBonusGroupTabItem},
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        data() {
            return {
                tab: null
            }
        },
        computed: {
            ...mapGetters([
                '_sports',
                '_statTypeByID'
            ]),
            statBonusGroups() {
                let self = this;
                return this._sports.map(function (sport) {
                    let statMeasurableBonuses = self.hero.statMeasurableBonuses.filter(function (statMeasurableBonus) {
                        let statType = self._statTypeByID(statMeasurableBonus.statTypeID);
                        return statType.sportID === sport.id;
                    });
                    return {
                        sport,
                        statMeasurableBonuses
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-tab-item class="py-2">
        <QualityStatBonusGroup
            v-for="(qualityBonusGroup, qualityType) in qualityStatBonusGroups"
            :key="qualityType"
            :quality-type="qualityBonusGroup.qualityType"
            :stat-types="qualityBonusGroup.statTypes"
            :percent-modifier="qualityBonusGroup.percentModifier"
        ></QualityStatBonusGroup>
    </v-tab-item>
</template>

<script>
    import {mapGetters} from 'vuex';
    import Sport from "../../../models/Sport";
    import QualityStatBonusGroup from "./QualityStatBonusGroup";

    export default {
        name: "StatBonusGroupTabItem",
        components: {QualityStatBonusGroup},
        props: {
            sport: {
                type: Sport,
                required: true
            },
            statMeasurableBonuses: {
                type: Array,
                required: true,
            }
        },
        computed: {
            ...mapGetters([
                '_qualityTypes',
                '_statTypeByID'
            ]),
            qualityStatBonusGroups() {
                let self = this;
                return this._qualityTypes.map(function (qualityType) {
                    let filteredStatMeasurableBonuses = self.statMeasurableBonuses.filter(function (statMeasurableBonus) {
                        return statMeasurableBonus.measurableTypeID === qualityType.id;
                    });
                    let statTypes = filteredStatMeasurableBonuses.map(function (statMeasurableBonus) {
                        return self._statTypeByID(statMeasurableBonus.statTypeID);
                    });
                    let percentModifier = filteredStatMeasurableBonuses[0] ? filteredStatMeasurableBonuses[0].percentModifier : 0;
                    return {
                        qualityType,
                        statTypes,
                        percentModifier
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>

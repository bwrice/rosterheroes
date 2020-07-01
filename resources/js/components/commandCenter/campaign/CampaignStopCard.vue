<template>
    <v-col cols="12">
        <v-sheet
            color="rgba(0,0,0,.3)"
            tile
            class="ma-1 rounded"
        >
            <v-row no-gutters align="center">
                <v-col cols="4" class="pa-1">
                    <MapViewPort
                        :view-box="province.viewBox"
                    >
                        <!-- Borders -->
                        <ProvinceVector
                            v-for="(province, uuid) in borders"
                            :key="uuid"
                            :province="province">
                        </ProvinceVector>

                        <ProvinceVector :province="province" :highlight="true"></ProvinceVector>
                    </MapViewPort>
                </v-col>
                <v-col cols="8">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-row no-gutters class="mb-3 pl-3">
                                <span class="title font-weight-light">{{campaignStop.compactQuest.name}}</span>
                            </v-row>
                        </v-col>
                        <v-col cols="12">
                            <v-row no-gutters align="center" class="pb-2 pr-2">
                                <v-col cols="8">
                                    <v-row no-gutters class="pl-3">
                                        <span class="subtitle-1 font-weight-light">{{sideQuestRatioText}}</span>
                                    </v-row>
                                </v-col>
                                <v-col cols="4">
                                    <v-row no-gutters justify="center">
                                        <v-btn
                                            small
                                            block
                                            color="error"
                                            @click="emitLeaveQuest"
                                        >
                                            Leave
                                        </v-btn>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-sheet>
    </v-col>
</template>

<script>
    import { mapGetters } from 'vuex';

    import CampaignStop from "../../../models/CampaignStop";
    import MapViewPort from "../realm/MapViewPort";
    import ProvinceVector from "../realm/ProvinceVector";

    export default {
        name: "CampaignStopCard",
        components: {ProvinceVector, MapViewPort},
        props: {
            campaignStop: {
                type: CampaignStop,
                required: true
            }
        },
        methods: {
            emitLeaveQuest(event) {
                this.$emit('leaveQuestClicked', this.campaignStop, event);
            }
        },
        computed: {
            ...mapGetters([
                '_provinceByUuid',
                '_provincesByUuids',
                '_squad'
            ]),
            province() {
                return this._provinceByUuid(this.campaignStop.provinceUuid);
            },
            borders() {
                return this._provincesByUuids(this.province.borderUuids);
            },
            sideQuestRatioText() {
                let text = 'Side Quests: (';
                text += this.campaignStop.sideQuestUuids.length + '/';
                text += this._squad.sideQuestsPerQuest + ')';
                return text;
            }
        }
    }
</script>

<style scoped>

</style>

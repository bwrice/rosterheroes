<template>
    <v-container>
        <v-row>
            <v-col cols="8" offset="2" sm="6" offset-sm="3" offset-md="4" md="4">
                <v-row no-gutters justify="space-around">
                    <v-col cols="6" class="px-2">
                        <v-btn
                            block
                            :color="'primary'"
                            :to="travelRoute"
                        >Travel</v-btn>
                    </v-col>
                    <v-col cols="6" class="px-2">
                        <v-btn
                            block
                            :color="'primary'"
                            :to="mapRoute"
                        >Map</v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" md="6" offset-md="0" lg="5" offset-lg="1" xl="4" offset-xl="2">
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">LOCAL: {{province.name}}</span>
                    </v-col>
                </v-row>
                <MapViewPort :view-box="_currentLocationProvince.viewBox">

                    <!-- Borders -->
                    <ProvinceVector
                        v-for="(province, uuid) in borders"
                        :key="uuid"
                        :province="province"
                    >
                    </ProvinceVector>

                    <ProvinceVector :province="_currentLocationProvince" :highlight="true"></ProvinceVector>
                </MapViewPort>
                <AvailableQuestsSection :show-travel-button="false" :title-override="'QUESTS'"></AvailableQuestsSection>
                <LocalMerchants></LocalMerchants>
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">SQUADS</span>
                    </v-col>
                    <v-col cols="12" v-if="_localSquads.length > 0" class="pb-2">
                        <PaginationBlock :items="_localSquads" :items-per-page="5">
                            <template v-slot:default="slotProps">
                                <LocalSquadPanel :local-squad="slotProps.item"></LocalSquadPanel>
                            </template>
                        </PaginationBlock>
                    </v-col>
                    <v-col cols="12" v-else>
                        <v-sheet color="rgba(255,255,255, 0.25)">
                            <v-row no-gutters class="pa-2" justify="center" align="center">
                                <span class="rh-op-70 subtitle-1">You're the lone squad in {{province.name}}</span>
                            </v-row>
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="12" offset-sm="2" sm="8" md="6" offset-md="0"  lg="5" xl="4">
                <v-row no-gutters>
                    <v-col cols="12">
                        <span class="title font-weight-thin">GLOBAL</span>
                    </v-col>
                </v-row>
                <MapViewPort :ocean-color="'#000'">

                    <!-- Borders -->
                    <ProvinceVector
                        v-for="(province, uuid) in provinces"
                        :key="uuid"
                        :province="province"
                        :fill-color="'#808080'"
                    >
                    </ProvinceVector>

                    <ProvinceVector :province="_currentLocationProvince" :fill-color="'#28bf5b'"></ProvinceVector>
                    <MapWindow :view-box="_currentLocationProvince.viewBox"></MapWindow>
                </MapViewPort>
                <CardSection :title="'STASHES'">
                    <PaginationBlock
                        v-if="_globalStashes.length"
                        :items="_globalStashes"
                        :items-per-page="6"
                    >
                        <template v-slot:default="slotProps">
                            <GlobalStashPanel
                                :global-stash="slotProps.item"
                            >
                            </GlobalStashPanel>
                        </template>
                    </PaginationBlock>
                    <EmptyNotifier :notification-text="'No global stashes'"></EmptyNotifier>
                </CardSection>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

    import { mapGetters } from 'vuex';

    import ProvinceVector from "../../realm/ProvinceVector";
    import MapViewPort from "../../realm/MapViewPort";
    import MapWindow from "../../realm/MapWindow";
    import QuestSummaryPanel from "../../realm/QuestSummaryPanel";
    import AvailableQuestsSection from "../../campaign/AvailableQuestsSection";
    import PaginationBlock from "../../global/PaginationBlock";
    import LocalSquadPanel from "../../realm/LocalSquadPanel";
    import LocalMerchants from "../../realm/LocalMerchants";
    import CardSection from "../../global/CardSection";
    import GlobalStashPanel from "../../realm/GlobalStashPanel";
    import EmptyNotifier from "../../global/EmptyNotifier";

    export default {
        name: "CurrentLocation",
        components: {
            EmptyNotifier,
            GlobalStashPanel,
            CardSection,
            LocalMerchants,
            LocalSquadPanel,
            PaginationBlock,
            AvailableQuestsSection,
            QuestSummaryPanel,
            MapWindow,
            MapViewPort,
            ProvinceVector,
        },

        computed: {
            ...mapGetters([
                '_currentLocationProvince',
                '_currentLocationQuests',
                '_provincesByUuids',
                '_provinces',
                '_localSquads',
                '_globalStashes'
            ]),
            bordersCount() {
                return this._currentLocationProvince.borderUuids.length;
            },
            borders() {
                return this._provincesByUuids(this._currentLocationProvince.borderUuids);
            },
            provinces() {
                return this._provinces.filter((province) => province.uuid !== this._currentLocationProvince.uuid);
            },
            mapRoute() {
                return {
                    name: 'realm-map',
                    params: {
                        squadSlug: this.$route.params.squadSlug
                    }
                }
            },
            travelRoute() {
                return {
                    name: 'travel',
                    params: {
                        squadSlug: this.$route.params.squadSlug
                    }
                }
            },
            // needed for borders mixin
            province() {
                return this._currentLocationProvince;
            }
        }
    }
</script>

<style scoped>

</style>

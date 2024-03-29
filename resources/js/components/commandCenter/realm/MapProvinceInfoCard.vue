<template>
    <v-sheet color="#466661" class="py-1 rounded">
        <v-row no-gutters>
            <v-col cols="12" class="px-2">
                <span class="title">PROVINCE INFO</span>
            </v-col>
            <v-col cols="6">
                <v-sheet
                    v-for="(info, title) in information"
                    :key="title"
                    tile
                    color="rgba(0,0,0, 0.25)"
                    class="mx-2 mb-1 px-2 rounded-sm">
                    <v-row no-gutters justify="space-between" align="center">
                        <span class="subtitle-1 font-weight-light">{{info.title.toUpperCase()}}:</span>
                        <span class="subtitle-1 font-weight-light">{{info.count}}</span>
                    </v-row>
                </v-sheet>
            </v-col>
            <v-col cols="6">
                <v-row no-gutters justify="center">
                    <span class="subtitle-2 underline rh-op-90">
                        Merchants
                    </span>
                </v-row>
                <template v-if="mapProvince.availableMerchants.length">
                    <v-row no-gutters justify="center" align="center">
                        <v-icon
                            v-for="(availableMerchant, id) in mapProvince.availableMerchants"
                            :key="id"
                            :color="getAvailableMerchantColor(availableMerchant)"
                        >
                            {{getAvailableMerchantIcon(availableMerchant)}}
                        </v-icon>
                    </v-row>
                </template>
                <template v-else>
                    <v-sheet color="rgba(255, 255, 255, 0.2)" class="mx-2 my-1 rounded-sm">
                        <v-row no-gutters justify="center" align="center">
                            <span class="body-2 my-1">
                                none
                            </span>
                        </v-row>
                    </v-sheet>
                </template>
            </v-col>
            <v-col cols="12">
                <v-row no-gutters justify="center" class="py-1">
                    <v-btn
                        color="primary"
                        @click="markForTravel"
                    >mark for travel</v-btn>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import {mapActions} from 'vuex';
    import {mapGetters} from 'vuex';
    import MapProvince from "../../../models/MapProvince";

    export default {
        name: "MapProvinceInfoCard",
        props: {
            mapProvince: {
                type: MapProvince,
                required: true
            }
        },
        methods: {
            ...mapActions([
                'markTravelDestination',
                'snackBarError'
            ]),
            markForTravel() {
                if (this._currentLocationProvince.uuid === this.mapProvince.provinceUuid) {
                    this.snackBarError({
                        text: "You're already in " + this._currentLocationProvince.name
                    });
                    return;
                }
                this.markTravelDestination(this.mapProvince.provinceUuid);
                this.$router.push({
                    name: 'travel',
                    params: {
                        squadSlug: this.$route.params.squadSlug
                    }
                })
            },
            getAvailableMerchantColor(availableMerchant) {
                console.log(availableMerchant);
                if (availableMerchant === 'recruitment-camp') {
                    return '#a461d4'
                }
                return 'accent'
            },
            getAvailableMerchantIcon(availableMerchant) {
                if (availableMerchant === 'recruitment-camp') {
                    return 'details'
                }
                return 'storefront'
            }
        },
        computed: {
            ...mapGetters([
                '_currentLocationProvince'
            ]),
            information() {
                return [
                    {
                        title: 'quests',
                        count: this.mapProvince.questsCount
                    },
                    {
                        title: 'squads',
                        count: this.mapProvince.squadsCount
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>

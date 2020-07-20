<template>
    <v-sheet color="#536a80"
             style="margin: 2px 0 2px 0"
             class="rounded"
    >
        <v-row no-gutters align="center" justify="center" class="mx-2">
            <div style="flex: 0 0 15%" class="py-1">
                <MapViewPort :view-box="province.viewBox" :rounded-size="'small'">

                    <!-- Borders -->
                    <ProvinceVector
                        v-for="(province, uuid) in borders"
                        :key="uuid"
                        :province="province"
                        :fill-color="'#808080'"
                    >
                    </ProvinceVector>

                    <!-- Province -->
                    <ProvinceVector :province="province" :highlight="true"></ProvinceVector>
                </MapViewPort>
            </div>
            <div class="flex-grow-1"></div>
            <span class="headline font-weight-light">{{province.name}} ({{globalStash.itemsCount}})</span>
            <div class="flex-grow-1"></div>
            <v-btn
                fab
                dark
                small
                color="#219cc2"
                elevation="2"
                @click="exploreProvince"
            >
                <v-icon>explore</v-icon>
            </v-btn>
        </v-row>
    </v-sheet>
</template>

<script>
    import GlobalStash from "../../../models/GlobalStash";
    import ProvinceVector from "./ProvinceVector";
    import MapViewPort from "./MapViewPort";

    import {mapGetters} from 'vuex';

    export default {
        name: "GlobalStashPanel",
        components: {MapViewPort, ProvinceVector},
        props: {
            globalStash: {
                type: GlobalStash,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_provinceByUuid',
                '_provincesByUuids'
            ]),
            province() {
                return this._provinceByUuid(this.globalStash.provinceUuid);
            },
            borders() {
                return this._provincesByUuids(this.province.borderUuids);
            }
        },
        methods: {
            exploreProvince() {
                this.province.goToRoute(this.$router, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>

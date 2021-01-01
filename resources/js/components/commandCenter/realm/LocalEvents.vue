<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">Local Events</span>
        </v-col>
        <v-col cols="12">
            <v-card>
                <v-virtual-scroll
                    v-if="eventMessages.length > 0"
                    :items="eventMessages"
                    :height="320"
                    :item-height="20"
                    :bench="4"
                >
                    <template v-slot:default="{ item }">
                        <span class="subtitle-1 font-weight-light">{{item}}</span>
                        <v-divider></v-divider>
                    </template>
                </v-virtual-scroll>
                <v-row
                    v-else
                    justify="center"
                    align="center"
                    :style="'height: 400px'"
                    class="no-gutters">
                    <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">No Recent Events</span>
                </v-row>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>

    import {mapGetters} from 'vuex';

    export default {
        name: "LocalEvents",
        computed: {
            ...mapGetters([
                '_localProvinceEvents',
                '_provinceByUuid'
            ]),
            eventMessages() {
                let provinceByUuid = this._provinceByUuid;
                return this._localProvinceEvents.map(function (provinceEvent) {
                    let message = provinceEvent.squad.name + ' enters ';
                    message += provinceByUuid(provinceEvent.provinceUuid).name + ' from ';
                    message += provinceByUuid(provinceEvent.extra.from.uuid).name;
                    return message;
                })
            }
        }
    }
</script>

<style scoped>

</style>

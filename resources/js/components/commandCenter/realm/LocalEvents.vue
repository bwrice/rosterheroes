<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">Local Events</span>
        </v-col>
        <v-col cols="12">
            <v-card>
                <v-virtual-scroll
                    v-if="_localProvinceEvents.length > 0"
                    :items="_localProvinceEvents"
                    :height="340"
                    :item-height="45"
                    :bench="4"
                >
                    <template v-slot:default="{ item }">
                        <component :is="eventComponent(item.eventType)" :provinceEvent="item"></component>
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
    import SquadEntersProvince from "./provinceEvents/SquadEntersProvince";
    import SquadLeavesProvince from "./provinceEvents/SquadLeavesProvince";
    import SquadJoinsQuest from "./provinceEvents/SquadJoinsQuest";

    export default {
        name: "LocalEvents",
        components: {SquadJoinsQuest, SquadLeavesProvince, SquadEntersProvince},
        methods: {
            eventComponent(eventType) {
                switch (eventType) {
                    case 'squad-enters-province':
                        return 'SquadEntersProvince';
                    case 'squad-leaves-province':
                        return 'SquadLeavesProvince';
                    case 'squad-joins-quest':
                        return 'SquadJoinsQuest';
                }
            }
        },
        computed: {
            ...mapGetters([
                '_localProvinceEvents',
                '_provinceByUuid'
            ])
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-card>
        <v-virtual-scroll
            v-if="provinceEvents.length > 0"
            :items="provinceEvents"
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
            :style="'height: 340px'"
            class="no-gutters">
            <span class="text-h6 text-lg-h5" style="color: rgba(255, 255, 255, 0.8)">No Recent Events</span>
        </v-row>
    </v-card>
</template>

<script>
    import SquadEntersProvince from "../provinceEvents/SquadEntersProvince";
    import SquadLeavesProvince from "../provinceEvents/SquadLeavesProvince";
    import SquadJoinsQuest from "../provinceEvents/SquadJoinsQuest";
    import SquadRecruitsHero from "./SquadRecruitsHero";
    import SquadSellsItem from "./SquadSellsItem";
    export default {
        name: "ProvinceEventsScroll",
        props: {
            provinceEvents: {
                type: Array,
                required: true
            }
        },
        components: {
            SquadSellsItem,
            SquadRecruitsHero,
            SquadJoinsQuest,
            SquadLeavesProvince,
            SquadEntersProvince
        },
        methods: {
            eventComponent(eventType) {
                switch (eventType) {
                    case 'squad-enters-province':
                        return 'SquadEntersProvince';
                    case 'squad-leaves-province':
                        return 'SquadLeavesProvince';
                    case 'squad-joins-quest':
                        return 'SquadJoinsQuest';
                    case 'squad-recruits-hero':
                        return 'SquadRecruitsHero';
                    case 'squad-sells-items':
                        return 'SquadSellsItem';
                }
            }
        },
    }
</script>

<style scoped>

</style>

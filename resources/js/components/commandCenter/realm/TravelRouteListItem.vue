<template>
    <v-sheet tile :color="color" class="mt-1 py-1 px-3">
        <v-row no-gutters justify="space-between" align="center">
            <span class="subtitle-2 opacity-75">
                {{travelDestination.province.name}}
            </span>
            <v-chip>
                {{travelCost}}
            </v-chip>
        </v-row>
    </v-sheet>
</template>

<script>
    import TravelRouteDestination from "../../../models/TravelRouteDestination";

    export default {
        name: "TravelRouteListItem",
        props: {
            travelDestination: {
                type: TravelRouteDestination,
                required: true
            },
            color: {
                type: String,
                default: 'success'
            }
        },
        computed: {
            travelCost() {
                let cost = this.travelDestination.cost;
                return cost ? cost : 'free';
            }
        }
    }
</script>

<style scoped>

</style>

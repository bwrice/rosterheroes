<template>
    <v-sheet tile :color="color" class="mt-1 py-1 px-3">
        <v-row no-gutters justify="space-between" align="center">
            <span class="subtitle-2 opacity-75">
                {{province.name}}
            </span>
            <v-chip>
                {{travelCost}}
            </v-chip>
        </v-row>
    </v-sheet>
</template>

<script>
    export default {
        name: "TravelRouteListItem",
        props: {
            province: {
                type: Object,
                required: true
            },
            color: {
                type: String,
                default: 'success'
            }
        },
        computed: {
            travelCost() {
                if (this.province.travelCost) {
                    return this.province.travelCost
                }
                return 'free';
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-sheet tile :color="color" class="pa-1">
        <v-layout>
            <v-flex class="xs8">
                {{province.name}}
            </v-flex>
            <v-flex class="xs4">
                <v-chip small>
                    {{travelCost}}
                </v-chip>
            </v-flex>
        </v-layout>
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

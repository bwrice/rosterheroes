<template>
    <v-sheet color="#466661" class="py-1">
        <v-row no-gutters>
            <v-col cols="12" class="px-2">
                <span class="title">PROVINCE INFO</span>
            </v-col>
            <v-col cols="12">
                <v-sheet
                    v-for="(info, title) in information"
                    :key="title"
                    tile
                    color="rgba(0,0,0, 0.25)"
                    class="mx-2 mb-1 px-2">
                    <v-row no-gutters justify="space-between" align="center">
                        <span class="subtitle-1 font-weight-light">{{info.title.toUpperCase()}}:</span>
                        <span class="subtitle-1 font-weight-light">{{info.count}}</span>
                    </v-row>
                </v-sheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import MapProvince from "../../../models/MapProvince";

    export default {
        name: "MapProvinceInfoCard",
        props: {
            mapProvince: {
                type: MapProvince,
                required: true
            }
        },
        computed: {
            information() {
                return [
                    {
                        title: 'quests',
                        count: this.mapProvince.questsCount
                    },
                    {
                        title: 'merchants',
                        count: this.mapProvince.merchantsCount
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

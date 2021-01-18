<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">Local Events</span>
        </v-col>
        <v-col cols="12">
            <ProvinceEventsScroll :province-events="_localProvinceEvents"></ProvinceEventsScroll>
        </v-col>
    </v-row>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceEventsScroll from "./provinceEvents/ProvinceEventsScroll";

    export default {
        name: "LocalEvents",
        components: {ProvinceEventsScroll},
        computed: {
            ...mapGetters([
                '_localProvinceEvents'
            ])
        }
    }
</script>

<style scoped>

</style>

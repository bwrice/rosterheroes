<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">Global Events</span>
        </v-col>
        <v-col cols="12">
            <ProvinceEventsScroll :province-events="_globalProvinceEvents"></ProvinceEventsScroll>
        </v-col>
    </v-row>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceEventsScroll from "./provinceEvents/ProvinceEventsScroll";

    export default {
        name: "GlobalEvents",
        components: {ProvinceEventsScroll},
        computed: {
            ...mapGetters([
                '_globalProvinceEvents'
            ])
        }
    }
</script>

<style scoped>

</style>

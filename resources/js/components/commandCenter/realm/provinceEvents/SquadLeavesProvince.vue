<template>
    <ProvinceEventScrollItem :province-event="provinceEvent">
        <ProvinceEventSquadText :name="squadName"></ProvinceEventSquadText>
         leaves
        <ProvinceEventProvinceText :province="provinceLeft"></ProvinceEventProvinceText>
         towards
        <ProvinceEventProvinceText :province="provinceEntered"></ProvinceEventProvinceText>
    </ProvinceEventScrollItem>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ProvinceEvent from "../../../../models/ProvinceEvent";
    import ProvinceEventSquadText from "./ProvinceEventSquadText";
    import ProvinceEventProvinceText from "./ProvinceEventProvinceText";
    import ProvinceEventScrollItem from "./ProvinceEventScrollItem";

    export default {
        name: "SquadLeavesProvince",
        components: {ProvinceEventScrollItem, ProvinceEventProvinceText, ProvinceEventSquadText},
        props: {
            provinceEvent: {
                type: ProvinceEvent,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_provinceByUuid'
            ]),
            squadName() {
                return this.provinceEvent.squad.name;
            },
            provinceLeft() {
                return this._provinceByUuid(this.provinceEvent.provinceUuid);
            },
            provinceEntered() {
                return this._provinceByUuid(this.provinceEvent.extra.to.uuid);
            }
        }
    }
</script>

<style scoped>

</style>

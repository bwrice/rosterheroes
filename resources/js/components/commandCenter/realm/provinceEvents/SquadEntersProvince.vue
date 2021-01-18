<template>
    <ProvinceEventScrollItem :province-event="provinceEvent">
        <ProvinceEventSquadText :name="squadName"></ProvinceEventSquadText>
         enters
        <ProvinceEventProvinceText :province="enteredProvince"></ProvinceEventProvinceText>
         from
        <ProvinceEventProvinceText :province="leavingProvince"></ProvinceEventProvinceText>
    </ProvinceEventScrollItem>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ProvinceEvent from "../../../../models/ProvinceEvent";
    import ProvinceEventSquadText from "./ProvinceEventSquadText";
    import ProvinceEventProvinceText from "./ProvinceEventProvinceText";
    import ProvinceEventScrollItem from "./ProvinceEventScrollItem";

    export default {
        name: "SquadEntersProvince",
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
            enteredProvince() {
                return this._provinceByUuid(this.provinceEvent.provinceUuid);
            },
            leavingProvince() {
                return this._provinceByUuid(this.provinceEvent.extra.from.uuid);
            }
        }
    }
</script>

<style scoped>

</style>

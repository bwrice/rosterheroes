<template>
    <ProvinceEventScrollItem :province-event="provinceEvent">
        <ProvinceEventSquadText :name="squadName"></ProvinceEventSquadText>
         joins
        <span class="error--text">{{questName}}</span>
         quest in
        <ProvinceEventProvinceText :province="province"></ProvinceEventProvinceText>
    </ProvinceEventScrollItem>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ProvinceEvent from "../../../../models/ProvinceEvent";
    import ProvinceEventScrollItem from "./ProvinceEventScrollItem";
    import ProvinceEventSquadText from "./ProvinceEventSquadText";
    import ProvinceEventProvinceText from "./ProvinceEventProvinceText";

    export default {
        name: "SquadJoinsQuest",
        components: {ProvinceEventProvinceText, ProvinceEventSquadText, ProvinceEventScrollItem},
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
            province() {
                return this._provinceByUuid(this.provinceEvent.provinceUuid);
            },
            questName() {
                return this.provinceEvent.extra.quest.name;
            }
        }
    }
</script>

<style scoped>

</style>

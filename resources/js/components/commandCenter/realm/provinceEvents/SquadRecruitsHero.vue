<template>
    <ProvinceEventScrollItem :province-event="provinceEvent">
        <ProvinceEventSquadText :name="squadName"></ProvinceEventSquadText>
         recruits {{heroRace.name}} {{heroClass.name}} from
        <span class="info--text">{{recruitmentCampName}}</span>
    </ProvinceEventScrollItem>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ProvinceEventScrollItem from "./ProvinceEventScrollItem";
    import ProvinceEvent from "../../../../models/ProvinceEvent";
    import ProvinceEventSquadText from "./ProvinceEventSquadText";

    export default {
        name: "SquadRecruitsHero",
        components: {ProvinceEventSquadText, ProvinceEventScrollItem},
        props: {
            provinceEvent: {
                type: ProvinceEvent,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_heroClassByID',
                '_heroRaceByID'
            ]),
            squadName() {
                return this.provinceEvent.squad.name;
            },
            heroName() {
                return this.provinceEvent.extra.hero.name;
            },
            recruitmentCampName() {
                return this.provinceEvent.extra.recruitmentCamp.name;
            },
            heroClass() {
                return this._heroClassByID(this.provinceEvent.extra.hero.heroClassID);
            },
            heroRace() {
                return this._heroRaceByID(this.provinceEvent.extra.hero.heroRaceID);
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-col cols="6">
        <span class="">{{heroRace.name}}</span>
        <HeroRaceIcon :hero-race="heroRace"></HeroRaceIcon>
    </v-col>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import HeroPostType from "../../../models/HeroPostType";
    import HeroRaceIcon from "../../icons/heroRaces/HeroRaceIcon";

    export default {
        name: "RecruitmentCampHeroPostTypeSelector",
        components: {HeroRaceIcon},
        props: {
            heroPostType: {
                type: HeroPostType,
                required: true
            }
        },
        mounted() {
            console.log("Hero Post Type");
            console.log(this.heroPostType);
        },
        computed: {
            ...mapGetters([
                '_heroRaceByID'
            ]),
            heroRace() {
                let heroRaceID = this.heroPostType.heroRaceIDs[0];
                return this._heroRaceByID(heroRaceID);
            }
        }
    }
</script>

<style scoped>

</style>

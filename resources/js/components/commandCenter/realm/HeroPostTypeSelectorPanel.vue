<template>
    <v-sheet
        :color="sheetColor"
        class="rounded mb-2"
        :elevation="elevation"
        style="cursor: pointer"
        @click.native="handleClick"
    >
        <v-row no-gutters justify="center" align="center">
            <v-col cols="3">
                <HeroRaceIcon :hero-race="heroRace" class="ma-2 ma-md-4"></HeroRaceIcon>
            </v-col>
            <v-col cols="9">
                <v-row no-gutters align="center" class="pb-1 pb-md-2">
                    <v-col cols="2" class="px-1 pt-1">
                        <GoldIcon></GoldIcon>
                    </v-col>
                    <v-col cols="10">
                        <v-row no-gutters align="center">
                            <span class="rh-op-85 text-center text-h6 text-md-h5 pl-4">{{heroPostType.recruitmentCost.toLocaleString()}}</span>
                            <v-spacer></v-spacer>
                            <span class="rh-op-85 text-center text-body-1 font-weight-light text-md-h6 pr-4">{{heroRace.name.toUpperCase()}}</span>
                        </v-row>
                    </v-col>
                </v-row>
                <v-row no-gutters align="center">
                    <div
                        class="text-center lighten-2 rounded-circle d-inline-flex align-center justify-center ma-1"
                        style="height: 24px; width: 24px"
                        :class="positionColor(position)"
                        v-for="(position, id) in positions" :key="id"
                    >
                        <span class="text-caption font-weight-bold">{{position.abbreviation}}</span>
                    </div>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import HeroPostType from "../../../models/HeroPostType";
    import HeroRaceIcon from "../../icons/heroRaces/HeroRaceIcon";
    import GoldIcon from "../../icons/GoldIcon";
    import PositionIcon from "../../icons/PositionIcon";
    import PositionChip from "../roster/PositionChip";

    export default {
        name: "HeroPostTypeSelectorPanel",
        components: {PositionChip, PositionIcon, GoldIcon, HeroRaceIcon},
        props: {
            heroPostType: {
                type: HeroPostType,
                required: true
            }
        },
        methods: {
            ...mapActions([
                'setRecruitmentHeroRace',
                'setRecruitmentHeroPostType'
            ]),
            positionColor(position) {
                switch (position.sportID) {
                    case 1:
                        return 'green';
                    case 2:
                        return 'red';
                    case 3:
                        return 'blue';
                    case 4:
                    default:
                        return 'orange';
                }
            },
            handleClick() {
                this.setRecruitmentHeroRace(this.heroRace);
                this.setRecruitmentHeroPostType(this.heroPostType);
            }
        },
        computed: {
            ...mapGetters([
                '_heroRaceByID',
                '_positionByID',
                '_recruitmentHeroPostType'
            ]),
            heroRace() {
                let heroRaceID = this.heroPostType.heroRaceIDs[0];
                return this._heroRaceByID(heroRaceID);
            },
            positions() {
                return this.heroRace.positionIDs.map(positionID => this._positionByID(positionID));
            },
            isSelected() {
                if (! this._recruitmentHeroPostType) {
                    return false;
                }
                return this._recruitmentHeroPostType.id === this.heroPostType.id;
            },
            elevation() {
                return this.isSelected ? 0 : 6;
            },
            sheetColor() {
                return this.isSelected ? '#447075' : '#445866';
            }
        }
    }
</script>

<style scoped>

</style>

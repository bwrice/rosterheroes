<template>
    <v-tab-item class="pa-1">
        <v-row no-gutters align="center">
            <v-col cols="4" md="3" class="pt-1 px-1">
                <v-row no-gutters>
                    <v-col cols="6">
                        <HeroRaceIcon :hero-race="heroRace"></HeroRaceIcon>
                    </v-col>
                    <v-col cols="6">
                        <HeroClassIcon :hero-class="heroClass"></HeroClassIcon>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="6" md="7">
                <v-row align="center" justify="center">
                    <span class="title font-weight-regular">{{heroSnapshot.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="2" :class="[$vuetify.breakpoint.name === 'xs' ? '' : 'pa-1']">
                <CombatPositionIcon :combat-position-id="heroSnapshot.combatPositionID" :attacker-mode="true" :tool-tip="false"></CombatPositionIcon>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="9">
                <PlayerSpiritSummaryPanel
                    v-if="heroSnapshot.playerSpirit"
                    :player-spirit="heroSnapshot.playerSpirit"
                ></PlayerSpiritSummaryPanel>
                <v-sheet
                    v-else
                    color="rgba(0, 0, 0, .2)"
                    class="ma-1 px-2">
                    <v-row justify="center" align="center">
                        <span class="subtitle-2 my-4">no spirit</span>
                    </v-row>
                </v-sheet>
            </v-col>
            <v-col cols="3">
                <v-row no-gutters justify="center" align="center">
                    <FantasyPowerIndicator :fantasy-power="heroSnapshot.fantasyPower"></FantasyPowerIndicator>
                </v-row>
            </v-col>
        </v-row>
        <HeroSnapshotRelativeResourceBar
            :hero-snapshot="heroSnapshot"
            :resource-name="'health'"
            :color="'error'"
            class="my-1"
        ></HeroSnapshotRelativeResourceBar>
        <HeroSnapshotRelativeResourceBar
            :hero-snapshot="heroSnapshot"
            :resource-name="'stamina'"
            :color="'accent'"
            class="my-1"
        ></HeroSnapshotRelativeResourceBar>
        <HeroSnapshotRelativeResourceBar
            :hero-snapshot="heroSnapshot"
            :resource-name="'mana'"
            :color="'primary'"
            class="my-1"
        ></HeroSnapshotRelativeResourceBar>
        <v-row no-gutters justify="space-between">
            <v-chip
                label
                color="primary"
                outlined
                class="ma-1"
            >
                Protection: {{heroSnapshot.protection}}
            </v-chip>
            <v-chip
                label
                color="primary"
                outlined
                class="ma-1"
            >
                Block Chance: {{heroSnapshot.blockChance}} %
            </v-chip>
        </v-row>
        <AttackSnapshotPanel v-for="(attackSnapshot, uuid) in heroSnapshot.attackSnapshots" :key="uuid" :attack-snapshot="attackSnapshot"></AttackSnapshotPanel>
    </v-tab-item>
</template>

<script>
    import {mapGetters} from 'vuex';
    import HeroSnapshot from "../../../models/HeroSnapshot";
    import HeroRaceIcon from "../../icons/heroRaces/HeroRaceIcon";
    import HeroClassIcon from "../../icons/heroClasses/HeroClassIcon";
    import CombatPositionIcon from "../../icons/CombatPositionIcon";
    import PlayerSpiritSummaryPanel from "../global/PlayerSpiritSummaryPanel";
    import FantasyPowerIndicator from "../global/FantasyPowerIndicator";
    import HeroSnapshotRelativeResourceBar from "./HeroSnapshotRelativeResourceBar";
    import AttackSnapshotPanel from "./AttackSnapshotPanel";

    export default {
        name: "HeroSnapshotTabItem",
        components: {
            AttackSnapshotPanel,
            HeroSnapshotRelativeResourceBar,
            FantasyPowerIndicator, PlayerSpiritSummaryPanel, CombatPositionIcon, HeroClassIcon, HeroRaceIcon},
        props: {
            heroSnapshot: {
                type: HeroSnapshot,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_heroRaceByID',
                '_heroClassByID'
            ]),
            heroRace() {
                return this._heroRaceByID(this.heroSnapshot.heroRaceID);
            },
            heroClass() {
                return this._heroClassByID(this.heroSnapshot.heroClassID);
            },
        }
    }
</script>

<style scoped>

</style>

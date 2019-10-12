<template>
    <v-app>
        <v-app-bar
            fixed
            app
            elevate-on-scroll
        >
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
                class="accent--text"
            ></v-app-bar-nav-icon>
            <router-view name="appBarContent"></router-view>
        </v-app-bar>
        <v-navigation-drawer
                fixed
                v-model="drawer"
                app
        >
            <router-view name="drawer"></router-view>
        </v-navigation-drawer>
        <v-content>
            <router-view></router-view>
        </v-content>
        <v-bottom-navigation
                :value="true"
                :height="76"
                fixed
                color="#332b38"
                app
        >
            <BarracksFooterButton></BarracksFooterButton>
            <RosterFooterButton></RosterFooterButton>
            <MapFooterButton></MapFooterButton>
            <CampaignFooterButton></CampaignFooterButton>
            <NationFooterButton></NationFooterButton>
        </v-bottom-navigation>
        <RhSnackBarAlert></RhSnackBarAlert>
    </v-app>
</template>

<script>

    import BarracksFooterButton from '../components/commandCenter/footer/BarracksFooterButton';
    import RosterFooterButton from '../components/commandCenter/footer/RosterFooterButton';
    import MapFooterButton from '../components/commandCenter/footer/MapFooterButton';
    import CampaignFooterButton from '../components/commandCenter/footer/CampaignFooterButton';
    import NationFooterButton from '../components/commandCenter/footer/NationFooterButton';

    import { mapGetters } from 'vuex'
    import { mapActions } from 'vuex'

    import RhSnackBarAlert from "../components/commandCenter/global/SnackBarAlert";

    export default {
        name: "CommandCenter",

        components: {
            RhSnackBarAlert,
            BarracksFooterButton,
            RosterFooterButton,
            MapFooterButton,
            CampaignFooterButton,
            NationFooterButton
        },

        mounted() {
            let route = this.$route;
            this.updateSquad(route);
            this.updateCurrentWeek();
            this.updateBarracks(route);
            this.updateRoster(route);
            this.updateCurrentLocation(route);
            this.setRealm();
            this.updateHeroClasses();
            this.updateHeroRaces();
            this.updatePositions();
            this.updateCombatPositions();
            this.updatePlayerSpirits();
            this.updateGames();
            this.updateTeams();
            this.updateSports();
        },

        data: function() {
            return {
                drawer: false
            }
        },
        methods: {
            ...mapActions([
                'updateSquad',
                'updateHero',
                'updateCurrentWeek',
                'updateBarracks',
                'updateRoster',
                'setPlayerSpiritsPool',
                'updatePlayerSpiritsPool',
                'updateCurrentLocation',
                'setRealm',
                'updateHeroClasses',
                'updateHeroRaces',
                'updatePositions',
                'updateCombatPositions',
                'updatePlayerSpirits',
                'updateGames',
                'updateTeams',
                'updateSports'
            ])
        },
        computed: {

            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_focusedHero'
            ]),
            toolBarTitle() {
                switch(this.$route.name) {
                    case 'barracks-hero':
                        return this._focusedHero(this.$route).name;

                    case 'barracks-main':
                    default:
                        return this._squad.name;
                }
            }
        }
    }
</script>

<style scoped>

</style>

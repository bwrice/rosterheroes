<template>
    <v-app>
        <v-app-bar
            fixed
            app
            color="#3a474a"
        >
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
                class="accent--text"
            ></v-app-bar-nav-icon>
            <router-view name="appBarContent"></router-view>
            <v-spacer></v-spacer>

            <NavBarWeekInfo></NavBarWeekInfo>
            <v-menu
                bottom
                left
                v-model="settings"
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                        color="accent"
                        icon
                        v-on="on"
                    >
                        <v-icon>settings</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item>
                        <v-spacer></v-spacer>
                        <v-icon @click="settings = false">close</v-icon>
                    </v-list-item>
                    <v-list-item>
                        <v-btn color="info" @click="logout">sign-out</v-btn>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-navigation-drawer
                fixed
                v-model="drawer"
                style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed"
                app
        >
            <NavigationDrawerContent></NavigationDrawerContent>
        </v-navigation-drawer>
        <v-main style="background-image: linear-gradient(#234a4a, #222626); background-attachment: fixed">
            <router-view></router-view>
        </v-main>
        <v-bottom-navigation
                :value="true"
                :height="76"
                fixed
                app
                style="background-color: #3a474a"
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
    import NavigationDrawerContent from "../components/commandCenter/navigationDrawer/NavigationDrawerContent";
    import NavBarWeekInfo from "../components/NavBarWeekInfo";

    export default {
        name: "CommandCenter",

        components: {
            NavBarWeekInfo,
            NavigationDrawerContent,
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
            this.updateHeroes(route);
            this.updateMobileStorage(route);
            this.updateGlobalStashes(route);
            this.updateCurrentCampaign(route);
            this.updateCurrentLocation(route);
            this.updateProvinces();
            this.updateTerritories();
            this.updateContinents();
            this.updateHeroClasses();
            this.updateHeroRaces();
            this.updateMeasurableTypes();
            this.updatePositions();
            this.updateCombatPositions();
            this.updateDamageTypes();
            this.updateTargetPriorities();
            this.updatePlayerSpirits();
            this.updateGames();
            this.updateTeams();
            this.updateSports();
            this.updateLeagues();
            this.updateStatTypes();
            this.updateSpellLibrary(route);
            this.updateUnopenedChests(route);
            this.updateHistoricCampaigns(route);
        },

        data: function() {
            return {
                drawer: false,
                settings: false
            }
        },
        methods: {
            ...mapActions([
                'updateSquad',
                'updateHero',
                'updateCurrentWeek',
                'updateHeroes',
                'updateMobileStorage',
                'updateGlobalStashes',
                'updateCurrentCampaign',
                'updateRoster',
                'setPlayerSpiritsPool',
                'updatePlayerSpiritsPool',
                'updateCurrentLocation',
                'updateProvinces',
                'updateTerritories',
                'updateContinents',
                'updateHeroClasses',
                'updateHeroRaces',
                'updateMeasurableTypes',
                'updatePositions',
                'updateCombatPositions',
                'updateDamageTypes',
                'updateTargetPriorities',
                'updatePlayerSpirits',
                'updateGames',
                'updateTeams',
                'updateSports',
                'updateSpellLibrary',
                'updateUnopenedChests',
                'updateLocalStash',
                'updateLeagues',
                'updateStatTypes',
                'updateHistoricCampaigns'
            ]),
            async logout() {
                await axios.post('/logout');
                window.location.replace('/');
            }
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

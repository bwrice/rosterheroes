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
            this.updateGlobalEvents();
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
        },

        watch: {
            $route(to, from) {
                this.handleRouteChange(to);
            },
            _currentLocationProvince(newProvince, oldProvince) {
                if (newProvince.uuid) {
                    window.Echo.channel('provinces.' + newProvince.uuid).listen('.new-province-event', e => this.handleProvinceEventCreated(e.uuid));
                }
                if (oldProvince.uuid) {
                    window.Echo.leave('provinces.' + oldProvince.uuid);
                }
            }
        },

        created() {
            this.handleRouteChange(this.$route);
            window.Echo.channel('provinces.global').listen('.new-province-event', e => this.handleNewGlobalProvinceEvent(e.uuid));
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
                'updateGlobalEvents',
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
                'updateHistoricCampaigns',
                'updateFocusedCampaign',
                'setupSideQuestReplay',
                'pauseSideQuestReplay',
                'handleProvinceEventCreated',
                'handleNewGlobalProvinceEvent'
            ]),
            async logout() {
                await axios.post('/logout');
                window.location.replace('/');
            },
            handleRouteChange(route) {
                this.pauseSideQuestReplay();
                this.handleCampaignRoutes(route);
            },

            /*
             * Handle all campaign route module updates in order with their dependencies
             */
            async handleCampaignRoutes(route) {
                let squadSlug = route.params.squadSlug;
                if (! this._historicCampaigns.length) {
                    await this.updateHistoricCampaigns(squadSlug);
                }

                /*
                 * If our current route has campaignUuid, make sure we have a focused campaign and it matches the uuid,
                 * otherwise, we need to update it after finding it in the historic campaigns. We have access to "_historicCampaigns"
                 * since we ensured they're updated above.
                 */
                let focusedCampaignUuid = route.params.campaignUuid;
                if (focusedCampaignUuid && (! this._focusedCampaign || this._focusedCampaign.uuid !== focusedCampaignUuid)) {
                    let focusedCampaign = this._historicCampaigns.find(campaign => campaign.uuid === focusedCampaignUuid);
                    await this.updateFocusedCampaign({focusedCampaign, squadSlug});
                }

                /*
                 * If our current route has sideQuestResultUuid, make sure our side-quest-replay module is setup for the
                 * correct sideQuestResult, otherwise set it up for the one from the route uuid. We have access to "_historicCampaignStops"
                 * because they will be updated when we trigger "updateFocusedCampaign" above
                 */
                let sideQuestResultUuid = route.params.sideQuestResultUuid;
                if (sideQuestResultUuid && (this._sideQuestResult.uuid !== sideQuestResultUuid)) {
                    let sqResults = [];
                    this._historicCampaignStops.forEach(campaignStop => sqResults.push(...campaignStop.sideQuestResults));
                    let sideQuestResult = sqResults.find(result => result.uuid === sideQuestResultUuid);
                    await this.setupSideQuestReplay(sideQuestResult);
                }
            }
        },
        computed: {

            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_focusedHero',
                '_historicCampaigns',
                '_historicCampaignStops',
                '_focusedCampaign',
                '_sideQuestResult',
                '_currentLocationProvince'
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

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
            <v-toolbar-title>
                {{ toolBarTitle }}
            </v-toolbar-title>
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

    import Squad from "../models/Squad";
    import Week from "../models/Week";
    import RhSnackBarAlert from "../components/commandCenter/global/SnackBarAlert";
    import HeroModel from "../models/HeroModel";

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
                'updateHeroRaces'
            ])
        },
        computed: {

            ...mapGetters([
                '_squad',
                '_currentWeek',
                '_focusedBarracksHero'
            ]),
            toolBarTitle() {
                switch(this.$route.name) {
                    case 'barracks-hero':
                        return this._focusedBarracksHero(this.$route).name;

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

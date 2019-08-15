<template>
    <v-app>
        <v-app-bar
                fixed
                app
        >
            <v-app-bar-nav-icon
                    @click.stop="drawer = !drawer"
                    class="accent--text"
            ></v-app-bar-nav-icon>
            <v-toolbar-title>{{ _squad.name }}</v-toolbar-title>
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
    import Hero from "../models/Hero";

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

        async mounted() {
            let route = this.$route;
            await this.updateSquad(route);

            let currentWeek = await Week.$find('current');
            this.setCurrentWeek(currentWeek);

            // If we land on a hero page, we need to update associated stores
            if (this.$route.params.heroSlug) {

                let hero = await Hero.$find(this.$route.params.heroSlug);
                this.updateHero(hero);

                if (this.$route.name === 'roster-hero') {
                    this.updatePlayerSpiritsPool();
                }
            }
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
                'setCurrentWeek',
                'setPlayerSpiritsPool',
                'updatePlayerSpiritsPool',
                'setMap'
            ])
        },
        computed: {

            ...mapGetters([
                '_squad',
                '_currentWeek'
            ])
        }
    }
</script>

<style scoped>

</style>

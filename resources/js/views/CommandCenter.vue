<template>
    <v-app dark>
        <v-toolbar fixed app>
            <v-toolbar-side-icon
                    class="accent--text"
                    @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>{{ _squad.name }}</v-toolbar-title>
        </v-toolbar>
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
        <v-bottom-nav
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
        </v-bottom-nav>
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
            let squad = await Squad.$find(this.$route.params.squadSlug);
            this.setSquad(squad);
            let currentWeek = await Week.$find('current');
            this.setCurrentWeek(currentWeek);
        },

        data: function() {
            return {
                drawer: false
            }
        },
        methods: {
            ...mapActions([
                'setSquad',
                'setCurrentWeek'
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
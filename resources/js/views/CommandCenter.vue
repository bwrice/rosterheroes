<template>
    <v-app dark>
        <v-toolbar fixed app>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title><span style="color: #ffc747">Squad Name</span></v-toolbar-title>
        </v-toolbar>
        <v-navigation-drawer
                fixed
                v-model="drawer"
                app
        >
            <v-list dense>
                <v-list-tile @click="">
                    <v-list-tile-action>
                        <v-icon>home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Home</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="">
                    <v-list-tile-action>
                        <v-icon>contact_mail</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Contact</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-content>
            <router-view></router-view>
        </v-content>
        <v-bottom-nav
                :active.sync="activeNavButton"
                :value="true"
                :height="76"
                fixed
                color="#332b38"
                app
        >
            <BarracksFooterButton :active-nav-button="activeNavButton"></BarracksFooterButton>
            <RosterFooterButton :active-nav-button="activeNavButton"></RosterFooterButton>
            <MapFooterButton :active-nav-button="activeNavButton"></MapFooterButton>
            <CampaignFooterButton :active-nav-button="activeNavButton"></CampaignFooterButton>
            <NationFooterButton :active-nav-button="activeNavButton"></NationFooterButton>
        </v-bottom-nav>
    </v-app>
</template>

<script>

    import BarracksFooterButton from '../components/commandCenter/footer/BarracksFooterButton';
    import RosterFooterButton from '../components/commandCenter/footer/RosterFooterButton';
    import MapFooterButton from '../components/commandCenter/footer/MapFooterButton';
    import CampaignFooterButton from '../components/commandCenter/footer/CampaignFooterButton';
    import NationFooterButton from '../components/commandCenter/footer/NationFooterButton'

    export default {
        name: "CommandCenter",

        components: {
            BarracksFooterButton,
            RosterFooterButton,
            MapFooterButton,
            CampaignFooterButton,
            NationFooterButton
        },

        created: function() {
            this.setInitialActiveNav()
        },

        data: function() {
            return {
                activeNavButton: '',
                drawer: false
            }
        },

        methods: {
            setInitialActiveNav: function() {
                this.activeNavButton = this.$route.name;
            }
        }
    }
</script>

<style scoped>

</style>
<template>
    <v-app dark>
        <v-toolbar fixed app>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>Squad Name</v-toolbar-title>
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
            <v-card>
                <div class="headline text-xs-center pa-5">
                    Active: {{ bottomNav }}
                </div>
            </v-card>
        </v-content>
        <v-bottom-nav
                :active.sync="bottomNav"
                :value="true"
                :height="76"
                absolute
                color="#332b38"
                app
        >
            <v-btn
                    color="teal"
                    flat
                    value="recent"
            >
                <span>Recent</span>
                <v-icon>history</v-icon>
            </v-btn>

            <v-btn
                    color="teal"
                    flat
                    value="favorites"
            >
                <span>Favorites</span>
                <v-icon>favorite</v-icon>
            </v-btn>

            <v-btn
                    color="teal"
                    flat
                    value="nearby"
            >
                <span>Nearby</span>
                <v-icon>place</v-icon>
            </v-btn>
            <v-btn
                    color="teal"
                    flat
                    value="axe"
            ><v-sheet
                    class="d-flex pa-2"
                    color="transparent"
                    height="60"
                    width="60"
            >
                <svg viewBox="0 0 60 60" width="100%"
                     height="100%" xmlns="http://www.w3.org/2000/svg"><title/>
                    <g data-name="Layer 1" id="Layer_1">
                        <path fill="#fff" d="M59.707.293A.99838.99838,0,0,0,58.34766.24219L22.85126,30.80853a2.89313,2.89313,0,0,0-3.30145.46783,43.85169,43.85169,0,0,0-4.2168,5.22412c-2.417,3.27393-5.42383,7.34814-8.7627,10.12012A7.47171,7.47171,0,0,1,3.86523,48.1543C1.22852,48.89355,0,52.07959,0,54a1.00013,1.00013,0,0,0,.293.707l5,5a.99964.99964,0,0,0,1.41406,0l2.40723-2.40771A6.07771,6.07771,0,0,0,10.2627,55.6792c4.32129-8.77393,9.60645-11.18506,13.85352-13.12207a21.656,21.656,0,0,0,3.94238-2.11328,2.93881,2.93881,0,0,0,1.1748-2.14795,2.99788,2.99788,0,0,0-.12524-1.05042L59.75781,1.65234A.99942.99942,0,0,0,59.707.293ZM33.82257,24h4.05206L27.94507,35.53137l-3.476-3.47681ZM8.46777,54.79541a4.0665,4.0665,0,0,1-.76758,1.08984L6,57.58594l-3.97461-3.9751c.14648-1.22705.94141-3.12793,2.37988-3.53076a9.40824,9.40824,0,0,0,3.44238-1.92041A36.38617,36.38617,0,0,0,12,44.00323v5.08051A32.88182,32.88182,0,0,0,8.46777,54.79541Zm18.39746-15.957a20.36262,20.36262,0,0,1-3.5791,1.89893A28.5745,28.5745,0,0,0,14,46.79218V41.582c1.05463-1.3399,2.03925-2.67157,2.94141-3.894a43.13051,43.13051,0,0,1,3.98535-4.96045c.6416-.6084,1.498.11133,1.83594.44873l4.18066,4.18213a1.014,1.014,0,0,1,.29492.792A.93142.93142,0,0,1,26.86523,38.83838ZM39.5968,22H36.14508L49.498,10.50146Z"/>
                    </g>
                </svg>
            </v-sheet>
            </v-btn>
        </v-bottom-nav>
    </v-app>
</template>

<script>
    export default {
        name: "CommandCenter",

        data: function() {
            return {
                bottomNav: '',
                drawer: false
            }
        }
    }
</script>

<style scoped>

</style>
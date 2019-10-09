import MapMain from "../components/commandCenter/views/map/MapMain";
import MapNavigationDrawer from "../components/commandCenter/views/map/MapNavigationDrawer";
import ContinentView from "../components/commandCenter/views/map/explore/ContinentView";
import TerritoryView from "../components/commandCenter/views/map/explore/TerritoryView";
import ProvinceView from "../components/commandCenter/views/map/explore/ProvinceView";
import ExploreView from "../components/commandCenter/views/map/explore/ExploreView";
import RealmView from "../components/commandCenter/views/map/explore/RealmView";
import TravelView from "../components/commandCenter/views/map/TravelView";
import CommandCenter from "../views/CommandCenter";
import SquadAppBar from "../components/commandCenter/appBar/SquadAppBar";

export const mapRoutes = {
    path: '/command-center/:squadSlug/map',
    meta: {
        footerButton: 'map'
    },
    component: CommandCenter,
    children: [
        {
            path: '',
            components: {
                default: MapMain,
                drawer: MapNavigationDrawer,
                appBar: SquadAppBar
            },
            component: MapMain,
            name: 'map-main',
            meta: {
                footerButton: 'map'
            }
        },
        {
            path: 'travel',
            components: {
                default: TravelView,
                drawer: MapNavigationDrawer,
                appBar: SquadAppBar
            },
            name: 'travel',
            meta: {
                footerButton: 'map'
            }
        },
        {
            path: 'explore',
            components: {
                default: ExploreView,
                drawer: MapNavigationDrawer,
                appBar: SquadAppBar
            },
            meta: {
                footerButton: 'map'
            },
            children: [
                {
                    path: '',
                    component: RealmView,
                    name: 'explore-realm',
                    meta: {
                        footerButton: 'map'
                    }
                },
                {
                    path: 'continents/:continentSlug',
                    component: ContinentView,
                    name: 'explore-continent',
                    meta: {
                        footerButton: 'map'
                    }
                },
                {
                    path: 'territories/:territorySlug',
                    component: TerritoryView,
                    name: 'explore-territory',
                    meta: {
                        footerButton: 'map'
                    }
                },
                {
                    path: 'provinces/:provinceSlug',
                    component: ProvinceView,
                    name: 'explore-province',
                    meta: {
                        footerButton: 'map'
                    }
                }
            ]
        },
    ]
};

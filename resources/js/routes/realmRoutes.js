import MapMain from "../components/commandCenter/views/map/MapMain";
import MapNavigationDrawer from "../components/commandCenter/views/map/MapNavigationDrawer";
import ContinentView from "../components/commandCenter/views/map/explore/ContinentView";
import TerritoryView from "../components/commandCenter/views/map/explore/TerritoryView";
import ProvinceView from "../components/commandCenter/views/map/explore/ProvinceView";
import ExploreView from "../components/commandCenter/views/map/explore/ExploreView";
import RealmView from "../components/commandCenter/views/map/explore/RealmView";
import TravelView from "../components/commandCenter/views/map/TravelView";
import CommandCenter from "../views/CommandCenter";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";

export const realmRoutes = {
    path: '/command-center/:squadSlug/realm',
    meta: {
        footerButton: 'realm'
    },
    component: CommandCenter,
    children: [
        {
            path: '',
            components: {
                default: MapMain,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            component: MapMain,
            name: 'map-main',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'travel',
            components: {
                default: TravelView,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'travel',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'explore',
            components: {
                default: ExploreView,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            meta: {
                footerButton: 'realm'
            },
            children: [
                {
                    path: '',
                    component: RealmView,
                    name: 'explore-realm',
                    meta: {
                        footerButton: 'realm'
                    }
                },
                {
                    path: 'continents/:continentSlug',
                    component: ContinentView,
                    name: 'explore-continent',
                    meta: {
                        footerButton: 'realm'
                    }
                },
                {
                    path: 'territories/:territorySlug',
                    component: TerritoryView,
                    name: 'explore-territory',
                    meta: {
                        footerButton: 'realm'
                    }
                },
                {
                    path: 'provinces/:provinceSlug',
                    component: ProvinceView,
                    name: 'explore-province',
                    meta: {
                        footerButton: 'realm'
                    }
                }
            ]
        },
    ]
};

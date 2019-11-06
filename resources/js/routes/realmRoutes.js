import CurrentLocation from "../components/commandCenter/views/realm/CurrentLocation";
import MapNavigationDrawer from "../components/commandCenter/views/realm/MapNavigationDrawer";
import ContinentView from "../components/commandCenter/views/realm/explore/ContinentView";
import TerritoryView from "../components/commandCenter/views/realm/explore/TerritoryView";
import ProvinceView from "../components/commandCenter/views/realm/explore/ProvinceView";
import ExploreView from "../components/commandCenter/views/realm/explore/ExploreView";
import RealmView from "../components/commandCenter/views/realm/explore/RealmView";
import TravelView from "../components/commandCenter/views/realm/TravelView";
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
                default: CurrentLocation,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            component: CurrentLocation,
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

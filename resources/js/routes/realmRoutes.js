import CurrentLocation from "../components/commandCenter/views/realm/CurrentLocation";
import MapNavigationDrawer from "../components/commandCenter/views/realm/MapNavigationDrawer";
import ContinentView from "../components/commandCenter/views/realm/explore/ContinentView";
import TerritoryView from "../components/commandCenter/views/realm/explore/TerritoryView";
import ProvinceView from "../components/commandCenter/views/realm/explore/ProvinceView";
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
                default: RealmView,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'explore-realm',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'explore/continents/:continentSlug',
            components: {
                default: ContinentView,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'explore-continent',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'explore/territories/:territorySlug',
            components: {
                default: TerritoryView,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'explore-territory',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'explore/provinces/:provinceSlug',
            components: {
                default: ProvinceView,
                drawer: MapNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'explore-province',
            meta: {
                footerButton: 'realm'
            }
        }
    ]
};

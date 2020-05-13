import CurrentLocation from "../components/commandCenter/views/realm/CurrentLocation";
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
                appBarContent: SquadAppBarContent
            },
            component: CurrentLocation,
            name: 'realm-main',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'travel',
            components: {
                default: TravelView,
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
                appBarContent: SquadAppBarContent
            },
            name: 'explore-province',
            meta: {
                footerButton: 'realm'
            }
        }
    ]
};

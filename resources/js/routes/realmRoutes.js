import CurrentLocation from "../components/commandCenter/views/realm/CurrentLocation";
import ContinentView from "../components/commandCenter/views/realm/map/ContinentView";
import TerritoryView from "../components/commandCenter/views/realm/map/TerritoryView";
import ProvinceView from "../components/commandCenter/views/realm/map/ProvinceView";
import RealmView from "../components/commandCenter/views/realm/map/RealmView";
import TravelView from "../components/commandCenter/views/realm/TravelView";
import CommandCenter from "../views/CommandCenter";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";
import ShopView from "../components/commandCenter/views/realm/merchants/ShopView";
import RecruitmentCampView from "../components/commandCenter/views/realm/merchants/RecruitmentCampView";

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
            path: 'shops/:merchantSlug',
            components: {
                default: ShopView,
                appBarContent: SquadAppBarContent
            },
            name: 'shop',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'recruitment-camps/:merchantSlug',
            components: {
                default: RecruitmentCampView,
                appBarContent: SquadAppBarContent
            },
            name: 'recruitment-camp',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'map',
            components: {
                default: RealmView,
                appBarContent: SquadAppBarContent
            },
            name: 'realm-map',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'map/continents/:continentSlug',
            components: {
                default: ContinentView,
                appBarContent: SquadAppBarContent
            },
            name: 'continent-map',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'map/territories/:territorySlug',
            components: {
                default: TerritoryView,
                appBarContent: SquadAppBarContent
            },
            name: 'territory-map',
            meta: {
                footerButton: 'realm'
            }
        },
        {
            path: 'map/provinces/:provinceSlug',
            components: {
                default: ProvinceView,
                appBarContent: SquadAppBarContent
            },
            name: 'province-map',
            meta: {
                footerButton: 'realm'
            }
        }
    ]
};

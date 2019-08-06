import MapMain from "../components/commandCenter/views/map/MapMain";
import MapBase from "../components/commandCenter/views/map/MapBase";
import MapNavigationDrawer from "../components/commandCenter/views/map/MapNavigationDrawer";
import ContinentView from "../components/commandCenter/views/map/explore/ContinentView";
import TerritoryView from "../components/commandCenter/views/map/explore/TerritoryView";
import ProvinceView from "../components/commandCenter/views/map/explore/ProvinceView";
import ExploreView from "../components/commandCenter/views/map/explore/ExploreView";
import RealmView from "../components/commandCenter/views/map/explore/RealmView";

export const mapRoutes = {
    path: '/command-center/:squadSlug/map',
    meta: {
        footerButton: 'map'
    },
    components: {
        default: MapBase,
        drawer: MapNavigationDrawer,
        meta: {
            footerButton: 'map'
        }
    },
    children: [
        {
            path: '',
            component: MapMain,
            name: 'map-main',
            meta: {
                footerButton: 'map'
            }
        },
        {
            path: 'explore',
            component: ExploreView,
            name: 'explore',
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

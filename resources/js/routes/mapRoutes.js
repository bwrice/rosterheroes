import MapMain from "../components/commandCenter/views/map/MapMain";
import MapBase from "../components/commandCenter/views/map/MapBase";
import MapNavigationDrawer from "../components/commandCenter/views/map/MapNavigationDrawer";
import ContinentView from "../components/commandCenter/views/map/ContinentView";
import TerritoryView from "../components/commandCenter/views/map/TerritoryView";
import ProvinceView from "../components/commandCenter/views/map/ProvinceView";

export const mapRoutes = {
    path: '/command-center/:squadSlug/map',
    meta: {
        footerButton: 'map'
    },
    components: {
        default: MapBase,
        drawer: MapNavigationDrawer
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
            path: 'continents/:continentSlug',
            component: ContinentView,
            name: 'map-continent',
            meta: {
                footerButton: 'map'
            }
        },
        {
            path: 'territories/:territorySlug',
            component: TerritoryView,
            name: 'map-territory',
            meta: {
                footerButton: 'map'
            }
        },
        {
            path: 'provinces/:provinceSlug',
            component: ProvinceView,
            name: 'map-province',
            meta: {
                footerButton: 'map'
            }
        }
    ]
};

import BarracksMain from '../components/commandCenter/views/barracks/BarracksMain'
import BarracksNavigationDrawer from '../components/commandCenter/views/barracks/BarracksNavigationDrawer'
import RosterMain from '../components/commandCenter/views/roster/RosterMain'
import RosterNavigationDrawer from '../components/commandCenter/views/roster/RosterNavigationDrawer'
import MapMain from '../components/commandCenter/views/map/MapMain'
import MapNavigationDrawer from '../components/commandCenter/views/map/MapNavigationDrawer'
import CampaignMain from '../components/commandCenter/views/campaign/CampaignMain'
import CampaignNavigationDrawer from '../components/commandCenter/views/campaign/CampaignNavigationDrawer'
import NationMain from '../components/commandCenter/views/nation/NationMain'
import NationNavigationDrawer from '../components/commandCenter/views/nation/NationNavigationDrawer'
import HeroBarracksView from '../components/commandCenter/views/barracks/HeroBarracksView'
import Barracks from "../components/commandCenter/views/barracks/Barracks";
import Roster from "../components/commandCenter/views/roster/Roster";
import HeroRosterView from "../components/commandCenter/views/roster/HeroRosterView";

export const routes = [
    {
        path: '/command-center/:squadSlug/barracks',
        name: 'barracks',
        meta: {
            footerButton: 'barracks'
        },
        components: {
            default: Barracks,
            drawer: BarracksNavigationDrawer
        },
        children: [
            {
                path: '',
                component: BarracksMain,
                name: 'barracks-main',
                meta: {
                    footerButton: 'barracks'
                }
            },
            {
                path: 'hero/:heroSlug',
                component: HeroBarracksView,
                name: 'barracks-hero',
                meta: {
                    footerButton: 'barracks'
                }
            }
        ]
    },
    {
        path: '/command-center/:squadSlug/roster',
        name: 'roster',
        meta: {
            footerButton: 'roster'
        },
        components: {
            default: Roster,
            drawer: RosterNavigationDrawer
        },
        children: [
            {
                path: '',
                component: RosterMain,
                name: 'barracks-main',
                meta: {
                    footerButton: 'barracks'
                }
            },
            {
                path: 'hero/:heroSlug',
                component: HeroRosterView,
                name: 'barracks-hero',
                meta: {
                    footerButton: 'barracks'
                }
            }
        ]
    },
    {
        path: '/command-center/:squadSlug/map',
        name: 'map',
        meta: {
            footerButton: 'map'
        },
        components: {
            default: MapMain,
            drawer: MapNavigationDrawer
        }
    },
    {
        path: '/command-center/:squadSlug/campaign',
        name: 'campaign',
        meta: {
            footerButton: 'campaign'
        },
        components: {
            default: CampaignMain,
            drawer: CampaignNavigationDrawer
        }
    },
    {
        path: '/command-center/:squadSlug/nation',
        name: 'nation',
        meta: {
            footerButton: 'nation'
        },
        components: {
            default: NationMain,
            drawer: NationNavigationDrawer
        }
    }
];
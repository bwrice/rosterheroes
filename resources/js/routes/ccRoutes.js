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
import HeroView from '../components/commandCenter/views/barracks/HeroView'

export const routes = [
    {
        path: '/command-center/:squadSlug/barracks',
        name: 'barracks',
        meta: {
            footerButton: 'barracks'
        },
        components: {
            default: BarracksMain,
            drawer: BarracksNavigationDrawer
        },
        children: [
            {
                path: 'hero/:heroSlug',
                component: HeroView,
                name: 'hero',
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
            default: RosterMain,
            drawer: RosterNavigationDrawer
        }
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
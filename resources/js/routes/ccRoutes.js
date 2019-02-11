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

export const routes = [
    {
        path: '/cc/:squadSlug/barracks',
        name: 'barracks',
        components: {
            default: BarracksMain,
            drawer: BarracksNavigationDrawer
        }
    },
    {
        path: '/cc/:squadSlug/roster',
        name: 'roster',
        components: {
            default: RosterMain,
            drawer: RosterNavigationDrawer
        }
    },
    {
        path: '/cc/:squadSlug/map',
        name: 'map',
        components: {
            default: MapMain,
            drawer: MapNavigationDrawer
        }
    },
    {
        path: '/cc/:squadSlug/campaign',
        name: 'campaign',
        components: {
            default: CampaignMain,
            drawer: CampaignNavigationDrawer
        }
    },
    {
        path: '/cc/:squadSlug/nation',
        name: 'nation',
        components: {
            default: NationMain,
            drawer: NationNavigationDrawer
        }
    }
];
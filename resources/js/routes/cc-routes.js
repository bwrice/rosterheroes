import BarracksMain from '../components/commandCenter/views/barracks/BarracksMain'
import RosterMain from '../components/commandCenter/views/roster/RosterMain'
import MapMain from '../components/commandCenter/views/map/MapMain'
import CampaignMain from '../components/commandCenter/views/campaign/CampaignMain'
import NationMain from '../components/commandCenter/views/nation/NationMain'

export const routes = [
    {
        path: '/cc/:squadSlug/barracks',
        name: 'barracks',
        components: {
            default: BarracksMain
        }
    },
    {
        path: '/cc/:squadSlug/roster',
        name: 'roster',
        components: {
            default: RosterMain
        }
    },
    {
        path: '/cc/:squadSlug/map',
        name: 'map',
        components: {
            default: MapMain
        }
    },
    {
        path: '/cc/:squadSlug/campaign',
        name: 'campaign',
        components: {
            default: CampaignMain
        }
    },
    {
        path: '/cc/:squadSlug/nation',
        name: 'nation',
        components: {
            default: NationMain
        }
    }
];
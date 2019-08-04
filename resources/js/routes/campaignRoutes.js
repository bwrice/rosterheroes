import CampaignMain from "../components/commandCenter/views/campaign/CampaignMain";
import CampaignNavigationDrawer from "../components/commandCenter/views/campaign/CampaignNavigationDrawer";

export const campaignRoutes = {
    path: '/command-center/:squadSlug/campaign',
    name: 'campaign',
    meta: {
        footerButton: 'campaign'
    },
    components: {
        default: CampaignMain,
        drawer: CampaignNavigationDrawer
    }
};

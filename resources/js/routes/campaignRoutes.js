import CampaignMain from "../components/commandCenter/views/campaign/CampaignMain";
import CampaignNavigationDrawer from "../components/commandCenter/views/campaign/CampaignNavigationDrawer";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";
import CommandCenter from "../views/CommandCenter";

export const campaignRoutes = {
    path: '/command-center/:squadSlug/campaign',
    meta: {
        footerButton: 'campaign'
    },
    component: CommandCenter,
    children: [
        {
            path: '',
            components: {
                default: CampaignMain,
                drawer: CampaignNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'campaign-main',
            meta: {
                footerButton: 'campaign'
            }
        }
    ]
};

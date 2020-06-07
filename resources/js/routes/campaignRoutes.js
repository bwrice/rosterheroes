import CampaignMain from "../components/commandCenter/views/campaign/CampaignMain";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";
import CommandCenter from "../views/CommandCenter";
import QuestView from "../components/commandCenter/views/campaign/QuestView";
import QuestResultView from "../components/commandCenter/views/campaign/QuestResultView";

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
                appBarContent: SquadAppBarContent
            },
            name: 'campaign-main',
            meta: {
                footerButton: 'campaign'
            }
        },
        {
            path: 'quests/:questSlug',
            components: {
                default: QuestView,
                appBarContent: SquadAppBarContent
            },
            name: 'campaign-quest',
            meta: {
                footerButton: 'campaign'
            }
        },
        {
            path: 'quest-result/:campaignStopUuid',
            components: {
                default: QuestResultView,
                appBarContent: SquadAppBarContent
            },
            name: 'quest-result',
            meta: {
                footerButton: 'campaign'
            }
        }
    ]
};

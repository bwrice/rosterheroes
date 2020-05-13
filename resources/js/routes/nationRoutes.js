import NationMain from "../components/commandCenter/views/nation/NationMain";
import CommandCenter from "../views/CommandCenter";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";

export const nationRoutes = {
    path: '/command-center/:squadSlug/nation',
    meta: {
        footerButton: 'nation'
    },
    component: CommandCenter,
    children: [
        {
            path: '',
            components: {
                default: NationMain,
                appBarContent: SquadAppBarContent
            },
            name: 'nation-main',
            meta: {
                footerButton: 'nation'
            }
        }
    ]
};

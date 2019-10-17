import NationMain from "../components/commandCenter/views/nation/NationMain";
import NationNavigationDrawer from "../components/commandCenter/views/nation/NationNavigationDrawer";
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
                drawer: NationNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'nation-main',
            meta: {
                footerButton: 'nation'
            }
        }
    ]
};

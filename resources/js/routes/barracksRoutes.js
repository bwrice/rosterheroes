import BarracksNavigationDrawer from "../components/commandCenter/views/barracks/BarracksNavigationDrawer";
import BarracksMain from "../components/commandCenter/views/barracks/BarracksMain";
import BarracksHeroView from "../components/commandCenter/views/barracks/BarracksHeroView";
import CommandCenter from "../views/CommandCenter";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";
import HeroAppBarContent from "../components/commandCenter/appBarContent/HeroAppBarContent";

export const barracksRoutes = {
    path: '/command-center/:squadSlug/barracks',
    meta: {
        footerButton: 'barracks'
    },
    component: CommandCenter,
    children: [
        {
            path: '',
            components: {
                default: BarracksMain,
                drawer: BarracksNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'barracks-main',
            meta: {
                footerButton: 'barracks'
            }
        },
        {
            path: 'hero/:heroSlug',
            components: {
                default: BarracksHeroView,
                drawer: BarracksNavigationDrawer,
                appBarContent: HeroAppBarContent
            },
            name: 'barracks-hero',
            meta: {
                footerButton: 'barracks'
            }
        }
    ]
};

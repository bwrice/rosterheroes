import BarracksNavigationDrawer from "../components/commandCenter/views/barracks/BarracksNavigationDrawer";
import BarracksMain from "../components/commandCenter/views/barracks/BarracksMain";
import BarracksHeroView from "../components/commandCenter/views/barracks/BarracksHeroView";
import CommandCenter from "../views/CommandCenter";
import HeroAppBar from "../components/commandCenter/appBar/HeroAppBar";
import SquadAppBar from "../components/commandCenter/appBar/SquadAppBar";

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
                appBar: SquadAppBar
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
                appBar: HeroAppBar
            },
            name: 'barracks-hero',
            meta: {
                footerButton: 'barracks'
            }
        }
    ]
};

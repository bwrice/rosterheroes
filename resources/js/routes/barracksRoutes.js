import Barracks from "../components/commandCenter/views/barracks/Barracks";
import BarracksNavigationDrawer from "../components/commandCenter/views/barracks/BarracksNavigationDrawer";
import BarracksMain from "../components/commandCenter/views/barracks/BarracksMain";
import HeroBarracksView from "../components/commandCenter/views/barracks/HeroBarracksView";

export const barracksRoutes = {
    path: '/command-center/:squadSlug/barracks',
    meta: {
        footerButton: 'barracks'
    },
    components: {
        default: Barracks,
        drawer: BarracksNavigationDrawer
    },
    children: [
        {
            path: '',
            component: BarracksMain,
            name: 'barracks-main',
            meta: {
                footerButton: 'barracks'
            }
        },
        {
            path: 'hero/:heroSlug',
            component: HeroBarracksView,
            name: 'barracks-hero',
            meta: {
                footerButton: 'barracks'
            }
        }
    ]
};

import RosterNavigationDrawer from "../components/commandCenter/views/roster/RosterNavigationDrawer";
import RosterMain from "../components/commandCenter/views/roster/RosterMain";
import HeroRosterView from "../components/commandCenter/views/roster/RosterHeroView";
import CommandCenter from "../views/CommandCenter";
import SquadAppBarContent from "../components/commandCenter/appBarContent/SquadAppBarContent";
import HeroAppBarContent from "../components/commandCenter/appBarContent/HeroAppBarContent";

export const rosterRoutes = {
    path: '/command-center/:squadSlug/roster',
    meta: {
        footerButton: 'roster'
    },
    component: CommandCenter,
    children: [
        {
            path: '',
            components: {
                default: RosterMain,
                drawer: RosterNavigationDrawer,
                appBarContent: SquadAppBarContent
            },
            name: 'roster-main',
            meta: {
                footerButton: 'roster'
            }
        },
        {
            path: 'hero/:heroSlug',
            components: {
                default: HeroRosterView,
                drawer: RosterNavigationDrawer,
                appBarContent: HeroAppBarContent
            },
            name: 'roster-hero',
            meta: {
                footerButton: 'roster'
            }
        }
    ]
};

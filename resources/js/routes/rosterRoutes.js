import RosterNavigationDrawer from "../components/commandCenter/views/roster/RosterNavigationDrawer";
import RosterMain from "../components/commandCenter/views/roster/RosterMain";
import HeroRosterView from "../components/commandCenter/views/roster/RosterHeroView";
import CommandCenter from "../views/CommandCenter";
import SquadAppBar from "../components/commandCenter/appBar/SquadAppBar";
import HeroAppBar from "../components/commandCenter/appBar/HeroAppBar";

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
                appBar: SquadAppBar
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
                appBar: HeroAppBar
            },
            name: 'roster-hero',
            meta: {
                footerButton: 'roster'
            }
        }
    ]
};

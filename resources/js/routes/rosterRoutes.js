import RosterNavigationDrawer from "../components/commandCenter/views/roster/RosterNavigationDrawer";
import RosterMain from "../components/commandCenter/views/roster/RosterMain";
import HeroRosterView from "../components/commandCenter/views/roster/RosterHeroView";
import CommandCenter from "../views/CommandCenter";

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
                drawer: RosterNavigationDrawer
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
                drawer: RosterNavigationDrawer
            },
            name: 'roster-hero',
            meta: {
                footerButton: 'roster'
            }
        }
    ]
};

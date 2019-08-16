import BaseView from "../components/commandCenter/views/BaseView";
import RosterNavigationDrawer from "../components/commandCenter/views/roster/RosterNavigationDrawer";
import RosterMain from "../components/commandCenter/views/roster/RosterMain";
import HeroRosterView from "../components/commandCenter/views/roster/RosterHeroView";

export const rosterRoutes = {
    path: '/command-center/:squadSlug/roster',
    meta: {
        footerButton: 'roster'
    },
    components: {
        default: BaseView,
        drawer: RosterNavigationDrawer
    },
    children: [
        {
            path: '',
            component: RosterMain,
            name: 'roster-main',
            meta: {
                footerButton: 'roster'
            }
        },
        {
            path: 'hero/:heroSlug',
            component: HeroRosterView,
            name: 'roster-hero',
            meta: {
                footerButton: 'roster'
            }
        }
    ]
};

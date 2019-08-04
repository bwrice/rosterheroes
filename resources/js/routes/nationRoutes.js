import NationMain from "../components/commandCenter/views/nation/NationMain";
import NationNavigationDrawer from "../components/commandCenter/views/nation/NationNavigationDrawer";

export const nationRoutes = {
    path: '/command-center/:squadSlug/nation',
    name: 'nation',
    meta: {
        footerButton: 'nation'
    },
    components: {
        default: NationMain,
        drawer: NationNavigationDrawer
    }
};

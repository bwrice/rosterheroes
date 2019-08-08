import {mapGetters} from "vuex";

export const provinceNavigationMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        ...mapGetters([
            '_squad'
        ]),
    },
    watch: {
        //
    },
    methods: {
        navigateToProvince(province) {
            console.log("Navigate to province");
            let provinceRoute = this.provinceRoute(this._squad, province);
            this.$router.push(provinceRoute);
        },
        provinceRoute(squad, province) {
            return {
                name: 'explore-province',
                params: {
                    squadSlug: squad.slug,
                    provinceSlug: province.slug
                }
            }
        }
    }
};

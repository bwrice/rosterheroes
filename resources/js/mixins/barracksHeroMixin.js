import {mapGetters} from "vuex";

export const barracksHeroMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        ...mapGetters([
            '_heroes',
            '_squad'
        ]),
        // TODO: replace usages with _focusedBarracksHero
        barracksHeroFromRoute() {
            let slug = this.$route.params.heroSlug;
            return this._heroes.find(hero => hero.slug === slug);
        },
        availableExperience() {
            if (! this.barracksHeroFromRoute) {
                return 0;
            }
            let squadExperience = this._squad.experience;
            return this.barracksHeroFromRoute.measurables.reduce(function (availableExperience, measurable) {
                return availableExperience - measurable.spentOnRaising;
            }, squadExperience);
        }
    },
    watch: {
        //
    },
    methods: {
        //
    }
};

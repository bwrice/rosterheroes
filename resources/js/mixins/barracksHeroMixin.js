import {mapGetters} from "vuex";

export const barracksHeroMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        ...mapGetters([
            '_barracksHeroes',
            '_squad'
        ]),
        barracksHeroFromRoute() {
            let slug = this.$route.params.heroSlug;
            return this._barracksHeroes.find(hero => hero.slug === slug);
        },
        availableExperience() {
            if (! this.barracksHeroFromRoute) {
                return 0;
            }
            let squadExperience = this._squad.experience;
            return this.barracksHeroFromRoute.measurables.reduce(function (availableExperience, measurable) {
                return availableExperience - measurable.spent_on_raising;
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

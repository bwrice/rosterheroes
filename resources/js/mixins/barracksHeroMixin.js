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
        barracksHero() {
            let slug = this.$route.params.heroSlug;
            let hero = this._barracksHeroes.find(hero => hero.slug === slug);
            if (hero) {
                return hero;
            }
            return {
                name: '',
                player_spirit: null,
                measurables: []
            }
        },
        availableExperience() {
            let squadExperience = this._squad.experience;
            return this.barracksHero.measurables.reduce(function (availableExperience, measurable) {
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

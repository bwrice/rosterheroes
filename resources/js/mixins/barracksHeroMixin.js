import {mapGetters} from "vuex";

export const barracksHeroMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        ...mapGetters([
            '_barracksHeroes'
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
                measurable: []
            }
        }
    },
    watch: {
        //
    },
    methods: {
        //
    }
};

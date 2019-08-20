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
            return this._barracksHeroes.find(hero => hero.slug === slug);
        }
    },
    watch: {
        //
    },
    methods: {
        //
    }
};

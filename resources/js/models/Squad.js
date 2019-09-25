import Model from './Model'
import HeroModel from "./HeroModel";

export default class Squad extends Model {

    primaryKey() {
        return 'slug';
    }

    get heroes() {
        let _heroes = [];
        if (this.heroPosts) {
            this.heroPosts.forEach(function (heroPost) {
                if (heroPost.hero) {
                    _heroes.push(new HeroModel(heroPost.hero));
                }
            });
        }
        return _heroes;
    }

    get availableSpiritEssence() {
        let available = this.spirit_essence;
        this.heroes.forEach(function(hero) {
            available -= hero.essenceUsed;
        });
        return available;
    }

    getHero(heroSlug) {
        this.heroes.forEach(function(hero) {
            if(hero.slug === heroSlug) {
                return hero;
            }
        });
        return {};
    }
}

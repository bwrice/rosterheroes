import Model from './Model'

import HeroPost from './HeroPost';

export default class Squad extends Model {

    constructor(squad) {
        super();
        this._spirit_essence = squad.spirit_essence ? squad.spirit_essence : 0;
        this._name = squad.name ? squad.name : '';
        this._heroPosts = squad.heroPosts ? squad.heroPosts : [];
        this._heroes = [];
        this._rosterFocusedHero = null;
    }

    get spiritEssence() {
        return this._spirit_essence;
    }

    get availableSpiritEssence() {
        let available = this._spirit_essence;
        this.heroes.forEach(function(hero) {
            available -= hero.essenceUsed;
        });
        return available;
    }

    get name() {
        return this._name;
    }

    get heroPosts() {
        let _heroPosts = [];
        this._heroPosts.forEach(function (heroPost) {
            _heroPosts.push(new HeroPost(heroPost))
        });
        return _heroPosts;
    }

    get heroes() {
        let _heroes = [];
        this.heroPosts.forEach(function (heroPost) {
            if (heroPost.hero) {
                _heroes.push(heroPost.hero);
            }
        });
        return _heroes;
    }

    get rosterFocusedHero() {
        return this._rosterFocusedHero;
    }
}
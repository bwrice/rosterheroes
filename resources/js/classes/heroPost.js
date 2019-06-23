import Hero from './hero';

export default class HeroPost {

    constructor(heroPost) {
        this._hero = heroPost.hero;
    }

    get hero() {
        if (this._hero) {
            return new Hero(this._hero);
        }
        return null;
    }
}
import Model from './Model'
import Hero from './Hero';

export default class HeroPost extends Model {

    constructor(heroPost) {
        super();
        this._hero = heroPost.hero;
    }

    get hero() {
        if (this._hero) {
            return new Hero(this._hero);
        }
        return null;
    }
}
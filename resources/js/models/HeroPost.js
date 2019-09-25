import Model from './Model'
import HeroModel from './HeroModel';

export default class HeroPost extends Model {

    constructor(heroPost) {
        super();
        this._hero = heroPost.hero;
    }

    get hero() {
        if (this._hero) {
            return new HeroModel(this._hero);
        }
        return null;
    }
}

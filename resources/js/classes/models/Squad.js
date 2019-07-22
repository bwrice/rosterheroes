import SquadAPIQuery from "./apiQueries/SquadAPIQuery";

export default class Squad {

    constructor() {
        this._data = {slug: ''};
    }

    async setData() {
        this._data = await SquadAPIQuery.$find(this.slug);
    }

    set slug(slug) {
        this._data.slug = slug;
    }

    get slug() {
        return this._data.slug;
    }

    get name() {
        return this._data.name ? this._data.name : '';
    }

    get heroPosts() {
        if (this._data.heroPosts) {
            // TODO
            return [];
        } else {
            return [];
        }
    }

    // get heroes() {
    //     let _heroes = [];
    //     this.heroPosts().forEach(function (heroPost) {
    //         if (heroPost.hero) {
    //             _heroes.push(heroPost.hero);
    //         }
    //     });
    //     return _heroes;
    // }
}
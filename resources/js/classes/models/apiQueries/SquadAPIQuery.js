import APIQuery from './APIQuery';
import HeroPostAPIQuery from "./HeroPostAPIQuery";

export default class SquadAPIQuery extends APIQuery {

    primaryKey() {
        return 'slug';
    }

    heroPosts() {
        return this.hasMany(HeroPostAPIQuery);
    }
}
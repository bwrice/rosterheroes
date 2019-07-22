import APIQuery from './APIQuery';

export default class HeroPostAPIQuery extends APIQuery {

    primaryKey() {
        return 'uuid';
    }
}
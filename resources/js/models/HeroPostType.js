export default class HeroPostType {

    constructor({id, name = '', heroRaceIDs = []}) {
        this.id = id;
        this.name = name;
        this.heroRaceIDs = heroRaceIDs;
    }
}

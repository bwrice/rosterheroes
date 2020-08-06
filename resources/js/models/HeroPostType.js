export default class HeroPostType {

    constructor({id, name = '', recruitmentCost = 0, heroRaceIDs = []}) {
        this.id = id;
        this.name = name;
        this.recruitmentCost = recruitmentCost;
        this.heroRaceIDs = heroRaceIDs;
    }
}

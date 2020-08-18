export default class HeroPostType {

    constructor({id, name = '', recruitmentCost = 0, recruitmentBonusSpiritEssence = 0, heroRaceIDs = []}) {
        this.id = id;
        this.name = name;
        this.recruitmentCost = recruitmentCost;
        this.recruitmentBonusSpiritEssence = recruitmentBonusSpiritEssence;
        this.heroRaceIDs = heroRaceIDs;
    }
}

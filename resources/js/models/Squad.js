export default class Squad {

    constructor({uuid, name = '', slug = '', spiritEssence = 0, gold = 0, experience = 0, favor = 0}) {
        this.name = name;
        this.uuid = uuid;
        this.slug = slug;
        this.spiritEssence = spiritEssence;
        this.gold = gold;
        this.experience = experience;
        this.favor = favor;
    }
}

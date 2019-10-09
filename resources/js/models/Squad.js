export default class Squad {

    constructor({uuid, name = '', slug = '', spiritEssence = 0, gold = 0, experience = 0, favor = 0}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.spiritEssence = spiritEssence;
        this.gold = gold;
        this.experience = experience;
        this.favor = favor;
    }
}

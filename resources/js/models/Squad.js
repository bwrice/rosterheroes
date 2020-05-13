
export default class Squad {

    constructor({uuid, name = '', slug = '', spiritEssence = 0, gold = 0, level = 1, experience = 0, experienceOverLevel = 0, experienceUntilNextLevel = 1, favor = 0, questsPerWeek, sideQuestsPerQuest}) {
        this.name = name;
        this.uuid = uuid;
        this.slug = slug;
        this.spiritEssence = spiritEssence;
        this.gold = gold;
        this.level = level;
        this.experience = experience;
        this.experienceOverLevel = experienceOverLevel;
        this.experienceUntilNextLevel = experienceOverLevel;
        this.favor = favor;
        this.questsPerWeek = questsPerWeek;
        this.sideQuestsPerQuest = sideQuestsPerQuest;
    }
}

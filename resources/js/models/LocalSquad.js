import LocalHero from "./LocalHero";

export default class LocalSquad {

    constructor({uuid, slug, name, level, localHeroes = []}) {
        this.uuid = uuid;
        this.slug = slug;
        this.name = name;
        this.level = level;
        this.localHeroes = localHeroes.map((localHero) => new LocalHero(localHero));
    }
}

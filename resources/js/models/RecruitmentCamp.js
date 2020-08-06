export default class HeroPostType {

    constructor({uuid, name = '', slug = '', heroPostTypes = [], heroClassIDs = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.heroPostTypes = heroPostTypes.map(heroPostType => new HeroPostType(heroPostType));
        this.heroClassIDs = heroClassIDs;
    }
}

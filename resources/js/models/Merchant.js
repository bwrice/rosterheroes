
export default class Merchant {

    constructor({name, slug, type}) {
        this.name = name;
        this.slug = slug;
        this.type = type;
    }

    get iconName() {
        if (this.type === 'recruitment-camp') {
            return 'details';
        }
        return 'storefront';
    }

    get iconColor() {
        if (this.type === 'recruitment-camp') {
            return '#ca80ff';
        }
        return 'accent';
    }

    getRoute(squadSlug) {

        let merchantSlugKey = 'shopSlug';
        if (this.type === 'recruitment-camp') {
            merchantSlugKey = 'recruitmentCampSlug'
        }
        let params = {
            squadSlug: squadSlug
        };
        params[merchantSlugKey] = this.slug;

        return {
            name: this.type,
            params: params
        };
    }
}

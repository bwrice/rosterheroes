
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
        return {
            name: this.type,
            params: {
                squadSlug: squadSlug,
                merchantSlug: this.slug
            }
        }
    }
}

import ViewBox from "./ViewBox";

export default class Continent {

    constructor({id, name = '', slug = '', color = '', viewBox}) {
        this.id = id;
        this.name = name;
        this.slug = slug;
        this._color = color;
        this._viewBox = viewBox ? new ViewBox(viewBox) : new ViewBox({});
    }

    get color() {
        return this._color;
    }

    get viewBox() {
        return this._viewBox;
    }

    goToRoute(router, route) {
        router.push(this.getRoute(route));
    }

    getRoute(route) {
        return {
            name: 'continent-map',
            params: {
                squadSlug: route.params.squadSlug,
                continentSlug: this.slug
            }
        }
    }
}

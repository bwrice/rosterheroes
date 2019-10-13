
export default class Continent {

    constructor({id, name = '', slug = '', realmColor = '', realmViewBox}) {
        this.id = id;
        this.name = name;
        this.slug = slug;
        this.realmColor = realmColor;
        this.realmViewBox = realmViewBox;
    }

    goToRoute(router, route) {
        router.push(this.getRoute(route));
    }

    getRoute(route) {
        return {
            name: 'explore-continent',
            params: {
                squadSlug: route.params.squadSlug,
                continentSlug: this.slug
            }
        }
    }
}

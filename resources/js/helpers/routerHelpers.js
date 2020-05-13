

export function getBarracksHeroRoute(hero, route) {
    let squadSlugParam = route.params.squadSlug;
    return {
        name: 'barracks-hero',
        params: {
            squadSlug: squadSlugParam,
            heroSlug: hero.slug
        }
    }
}

export function getRosterHeroRoute(hero, route) {
    let squadSlugParam = route.params.squadSlug;
    return {
        name: 'roster-hero',
        params: {
            squadSlug: squadSlugParam,
            heroSlug: hero.slug
        }
    }
}

export function routesMatch(routeOne, routeTwo){
    if (routeOne.name !== routeTwo.name) {
        return false;
    }
    return _.isEqual(routeOne.params, routeTwo.params);
}

export function getBaseRoute(route, routeName) {
    let squadSlug = route.params.squadSlug;
    return {
        name: routeName,
        params: {
            squadSlug
        }
    }
}

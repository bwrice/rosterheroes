<template>
    <v-list nav>
        <NavListGroup
            :group-title="'Barracks'"
            :main-route-name="'barracks-main'"
        >
            <BarracksHeroNavListItem
                v-for="(hero, uuid) in _heroes"
                :key="uuid"
                :hero="hero"
            ></BarracksHeroNavListItem>
        </NavListGroup>
        <NavListGroup
            :group-title="'Roster'"
            :main-route-name="'roster-main'"
        >

        </NavListGroup>
        <NavListGroup
            :group-title="'Realm'"
            :main-route-name="'realm-main'"
        >
            <NavListItem
                :title="'Explore'"
                :to="exploreRoute"
            ></NavListItem>
            <NavListItem
                :title="'Travel'"
                :to="travelRoute"
            ></NavListItem>
        </NavListGroup>
        <NavListGroup
            :group-title="'Campaign'"
            :main-route-name="'campaign-main'"
        >

        </NavListGroup>
        <NavListGroup
            :group-title="'Nation'"
            :main-route-name="'nation-main'"
        >

        </NavListGroup>
    </v-list>
</template>

<script>
    import {mapGetters} from 'vuex';
    import * as routerHelpers from "../../helpers/routerHelpers"
    import BarracksHeroNavListItem from "./BarracksHeroNavListItem";
    import NavListGroup from "./NavListGroup";
    import NavListItem from "./NavListItem";
    export default {
        name: "NavigationDrawerContent",
        components: {NavListItem, NavListGroup, BarracksHeroNavListItem},
        computed: {
            ...mapGetters([
                '_heroes',
            ]),
            travelRoute() {
                return routerHelpers.getBaseRoute(this.$route, 'travel');
            },
            exploreRoute() {
                return routerHelpers.getBaseRoute(this.$route, 'explore-realm');
            }
        }
    }
</script>

<style scoped>

</style>

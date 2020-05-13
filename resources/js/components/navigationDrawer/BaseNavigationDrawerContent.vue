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

        </NavListGroup>
        <NavListGroup
            :group-title="'Campaign'"
            :main-route-name="'roster-main'"
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
    import BarracksHeroNavListItem from "./BarracksHeroNavListItem";
    import NavListGroup from "./NavListGroup";
    import NavListItem from "./NavListItem";
    export default {
        name: "BaseNavigationDrawerContent",
        components: {NavListItem, NavListGroup, BarracksHeroNavListItem},
        computed: {
            ...mapGetters([
                '_heroes',
            ]),
            barracksMainRoute() {
                let squadSlug = this.$route.params.squadSlug
                return {
                    name: 'barracks-main',
                    params: {
                        squadSlug
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

<template>
    <v-list nav>
        <NavListGroup :group-title="'Barracks'">
            <NavListItem
                :title="'Barracks Home'"
                :to="barracksMainRoute"
            ></NavListItem>
            <BarracksHeroNavListItem
                v-for="(hero, uuid) in _heroes"
                :key="uuid"
                :hero="hero"
            ></BarracksHeroNavListItem>
        </NavListGroup>
        <v-list-item>
            <v-list-item-content>Roster</v-list-item-content>
        </v-list-item>
        <v-list-item>
            <v-list-item-content>Realm</v-list-item-content>
        </v-list-item>
        <v-list-item>
            <v-list-item-content>Campaign</v-list-item-content>
        </v-list-item>
        <v-list-item>
            <v-list-item-content>Nation</v-list-item-content>
        </v-list-item>
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

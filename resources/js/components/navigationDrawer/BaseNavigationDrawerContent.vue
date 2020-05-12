<template>
    <v-list nav>
        <v-list-group color="primary">
            <template v-slot:activator>
                <v-list-item-content>
                    <v-list-item-title>Barracks</v-list-item-title>
                </v-list-item-content>
            </template>
            <BarracksHeroNavListItem
                v-for="(hero, uuid) in _heroes"
                :key="uuid"
                :hero="hero"
            ></BarracksHeroNavListItem>
        </v-list-group>
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
    export default {
        name: "BaseNavigationDrawerContent",
        components: {BarracksHeroNavListItem},
        computed: {
            ...mapGetters([
                '_heroes',
            ]),
        }
    }
</script>

<style scoped>

</style>

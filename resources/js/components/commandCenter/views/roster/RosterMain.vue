<template>
    <SingleColumnLayout>
        <template v-slot:column-one>
            <v-row class="no-gutters">
                <v-col cols="12" class="py-2">
                    <v-row no-gutters align="end">
                        <span class="display-3 font-weight-bold" style="color: rgba(255, 255, 255, .75)">{{_availableSpiritEssence.toLocaleString()}}</span>
                        <span class="subtitle-1 px-2" style="color: rgba(255, 255, 255, .75)">Spirit Essence</span>
                    </v-row>
                </v-col>
                <v-col cols="12" class="py-2">
                    <span class="title font-weight-thin">ROSTER</span>
                </v-col>
                <v-col cols="12" v-for="(hero, uuid) in _heroes" :key="uuid">
                    <HeroRosterCard :hero="hero">
                        <template slot="body">
                            <div class="mx-1" v-if="hero.playerSpirit">
                                <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                                    <template v-slot:spirit-actions>
                                        <EditSpiritButton :hero="hero"></EditSpiritButton>
                                        <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpirit"></RemoveSpiritButton>
                                    </template>
                                </PlayerSpiritPanel>
                            </div>
                            <v-row v-else justify="center" align="center" no-gutters class="mx-2">
                                <AddSpiritRouterButton :hero-slug="hero.slug" :btn-classes="{'mx-2': true}"></AddSpiritRouterButton>
                            </v-row>
                        </template>
                    </HeroRosterCard>
                </v-col>
            </v-row>
        </template>
    </SingleColumnLayout>
</template>

<script>


    import HeroRosterCard from '../../roster/HeroRosterCard';
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import EditSpiritButton from "../../roster/EditSpiritButton";
    import PlayerSpiritPanel from "../../roster/PlayerSpiritPanel";

    import { mapGetters } from 'vuex'
    import SingleColumnLayout from "../../layouts/SingleColumnLayout";
    import AddSpiritRouterButton from "../../global/AddSpiritRouterButton";

    export default {
        name: "RosterMain",
        components: {
            AddSpiritRouterButton,
            SingleColumnLayout,
            HeroRosterCard,
            EditSpiritButton,
            RemoveSpiritButton,
            PlayerSpiritPanel
        },
        computed: {
            ...mapGetters([
                '_heroes',
                '_availableSpiritEssence'
            ])
        },
    }
</script>

<style scoped>

</style>

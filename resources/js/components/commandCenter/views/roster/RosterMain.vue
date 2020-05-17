<template>
    <SingleColumnLayout>
        <template v-slot:column-one>
            <v-row class="no-gutters">
                <v-col cols="12">
                    <v-row no-gutters class="mb-2">
                        <span class="title font-weight-thin">SPIRIT ESSENCE</span>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-sheet color="#576269">
                        <v-row no-gutters>
                            <v-col cols="8" class="d-flex flex-column justify-center px-2 pb-1">
                                <span class="subtitle-1 font-weight-light ma-1 underline">Essence Remaining</span>
                                <v-sheet color="rgba(0,0,0, 0.5)" class="pa-2">
                                    <span class="display-3 font-weight-bold" style="color: rgba(163, 255, 217, .85)">{{_availableSpiritEssence.toLocaleString()}}</span>
                                </v-sheet>
                            </v-col>
                            <v-col cols="4">
                                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 mt-2 mb-1">
                                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                                        <span class="overline">Total</span>
                                        <span class="caption">30,000</span>
                                    </v-row>
                                </v-sheet>
                                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 my-1">
                                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                                        <span class="overline">Tot./Hero</span>
                                        <span class="caption">7,500</span>
                                    </v-row>
                                </v-sheet>
                                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 mt-1 mb-2">
                                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                                        <span class="overline">Rem./Hero</span>
                                        <span class="caption">5,391</span>
                                    </v-row>
                                </v-sheet>
<!--                                <span class="overline">Tot./Player</span>-->
<!--                                <span class="caption">7,500</span>-->
<!--                                <span class="overline">Rem./Player</span>-->
<!--                                <span class="caption">0</span>-->
                            </v-col>
                        </v-row>
                    </v-sheet>
                </v-col>
                <v-col cols="12">
                    <v-row no-gutters class="my-2">
                        <span class="title font-weight-thin">ROSTER</span>
                    </v-row>
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

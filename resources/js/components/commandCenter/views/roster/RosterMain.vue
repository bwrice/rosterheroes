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
                    <SpiritEssenceCard></SpiritEssenceCard>
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
    import SpiritEssenceCard from "../../roster/SpiritEssenceCard";

    export default {
        name: "RosterMain",
        components: {
            SpiritEssenceCard,
            AddSpiritRouterButton,
            SingleColumnLayout,
            HeroRosterCard,
            EditSpiritButton,
            RemoveSpiritButton,
            PlayerSpiritPanel
        },
        computed: {
            ...mapGetters([
                '_heroes'
            ])
        },
    }
</script>

<style scoped>

</style>

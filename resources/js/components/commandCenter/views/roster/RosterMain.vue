<template>
    <SingleColumnLayout>
        <template v-slot:column-one>
            <v-card>
                <span class="display-3 px-1">{{_availableSpiritEssence.toLocaleString()}}</span> Spirit Essence Available
                <HeroRosterCard v-for="(hero, uuid) in _heroes" :key="uuid" :hero="hero">
                    <template slot="body">
                        <div v-if="hero.playerSpirit">
                            <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                                <template v-slot:spirit-actions>
                                    <EditSpiritButton :hero="hero"></EditSpiritButton>
                                    <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpirit"></RemoveSpiritButton>
                                </template>
                            </PlayerSpiritPanel>
                        </div>
                        <div v-else>
                            <EditSpiritButton :hero="hero"></EditSpiritButton>
                        </div>
                    </template>
                </HeroRosterCard>
            </v-card>
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

    export default {
        name: "RosterMain",
        components: {
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

<template>
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
        <v-col cols="12" v-for="(hero, uuid) in heroes" :key="uuid">
            <HeroRosterCard :hero="hero">
                <template slot="body">
                    <v-sheet
                        class="rounded-sm mx-1"
                        v-if="hero.playerSpirit"
                    >
                        <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                            <template v-slot:spirit-actions>
                                <EditSpiritButton :hero="hero"></EditSpiritButton>
                                <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpirit"></RemoveSpiritButton>
                            </template>
                        </PlayerSpiritPanel>
                    </v-sheet>
                    <v-row v-else justify="center" align="center" no-gutters class="mx-2">
                        <AddSpiritRouterButton :hero-slug="hero.slug" :btn-classes="{'mx-2': true}"></AddSpiritRouterButton>
                    </v-row>
                </template>
            </HeroRosterCard>
        </v-col>
    </v-row>
</template>

<script>
    import SpiritEssenceCard from "./SpiritEssenceCard";
    import HeroRosterCard from "./HeroRosterCard";
    import PlayerSpiritPanel from "./PlayerSpiritPanel";
    import EditSpiritButton from "./EditSpiritButton";
    import RemoveSpiritButton from "./RemoveSpiritButton";
    import AddSpiritRouterButton from "../global/AddSpiritRouterButton";
    export default {
        name: "EssenceAndRosterColumn",
        components: {
            AddSpiritRouterButton,
            RemoveSpiritButton,
            EditSpiritButton,
            PlayerSpiritPanel,
            HeroRosterCard,
            SpiritEssenceCard
        },
        props: {
            heroes: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

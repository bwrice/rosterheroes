<template>
    <v-container>
        <v-row>
            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" offset-lg="1" lg="5" offset-xl="2" xl="4">
                <v-row no-gutters>
                    <v-col cols="12">
                        <HeroRosterCard
                            v-if="hero"
                            :hero="hero"
                        >
                            <template slot="body">
                                <div class="mx-1" v-if="hero.playerSpirit">
                                    <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                                        <template v-slot:spirit-actions>
                                            <RemoveSpiritButton
                                                :hero="hero"
                                                :player-spirit="hero.playerSpirit"
                                            >
                                            </RemoveSpiritButton>
                                        </template>
                                    </PlayerSpiritPanel>
                                </div>
                                <v-row v-else no-gutters justify="center" align="center">
                                    <v-col cols="12">
                                        <v-sheet color="rgba(0, 0, 0, .3)" class="mx-2 my-1">
                                            <v-row no-gutters justify="center" align="center">
                                                <span class="title pa-2 font-weight-thin">(empty)</span>
                                            </v-row>
                                        </v-sheet>
                                    </v-col>
                                </v-row>
                            </template>
                        </HeroRosterCard>
                        <v-skeleton-loader
                            v-else
                            type="list-item-three-line"
                            height="84"
                            class="mb-2"
                        ></v-skeleton-loader>
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col cols="12">
                        <PlayerSpiritPool></PlayerSpiritPool>
                    </v-col>
                </v-row>
            </v-col>

            <v-col cols="12" offset-sm="2" sm="8" offset-md="0" md="6" lg="5" xl="4">
                <EssenceAndRosterColumn :heroes="otherSquadHeroes"></EssenceAndRosterColumn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { mapGetters } from 'vuex';

    import PlayerSpiritPanel from '../../roster/PlayerSpiritPanel';
    import AddSpiritButton from "../../roster/AddSpiritButton";
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import HeroRosterCard from "../../roster/HeroRosterCard";
    import SingleColumnLayout from "../../layouts/SingleColumnLayout";
    import PaginationBlock from "../../global/PaginationBlock";
    import EssenceAndRosterColumn from "../../roster/EssenceAndRosterColumn";
    import PlayerSpiritPool from "../../roster/PlayerSpiritPool";

    export default {
        name: "RosterHeroView",

        components: {
            PlayerSpiritPool,
            EssenceAndRosterColumn,
            PaginationBlock,
            SingleColumnLayout,
            HeroRosterCard,
            RemoveSpiritButton,
            AddSpiritButton,
            PlayerSpiritPanel
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_heroes',
                '_currentWeek',
                '_focusedHero',
                '_loadingSpirits',
                '_playerSpirits',
                '_heroRaceByID'
            ]),
            rosterPage() {
                return '/command-center/' + this.$route.params.squadSlug + '/roster' ;
            },
            hero() {
                return this._focusedHero(this.$route, true);
            },
            otherSquadHeroes() {
                let currentHero = this.hero;
                return this._heroes.filter((hero) => hero.uuid !== currentHero.uuid);
            },
        },
    }
</script>

<style scoped>

</style>

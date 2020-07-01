<template>
    <v-sheet color="#576269" class="rounded">
        <v-row no-gutters align="end">
            <v-col cols="8" class="d-flex flex-column justify-center px-2 pb-2">
                <span class="text-body-1 ma-1">Essence Remaining</span>
                <v-sheet color="rgba(0,0,0, 0.5)" class="pa-2 rounded-sm">
                    <span class="text-h2 font-weight-bold" style="color: rgba(163, 255, 217, .85)">{{_availableSpiritEssence.toLocaleString()}}</span>
                </v-sheet>
            </v-col>
            <v-col cols="4">
                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 mt-2 mb-1 rounded-sm">
                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                        <span class="text-body-2 text-decoration-underline">Total</span>
                        <span class="text-caption">{{_totalSpiritEssence.toLocaleString()}}</span>
                    </v-row>
                </v-sheet>
                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 my-1 rounded-sm">
                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                        <span class="text-body-2 text-decoration-underline">Tot./Hero</span>
                        <span class="text-caption">{{totalPerHero}}</span>
                    </v-row>
                </v-sheet>
                <v-sheet color="rgba(0,0,0, 0.5)" class="px-2 mx-2 mt-1 mb-2 rounded-sm">
                    <v-row no-gutters class="d-flex flex-column justify-center align-end">
                        <span class="text-body-2 text-decoration-underline">Rem./Hero</span>
                        <span class="text-caption">{{remainingPerHero}}</span>
                    </v-row>
                </v-sheet>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        name: "SpiritEssenceCard",

        computed: {
            ...mapGetters([
                '_heroes',
                '_availableSpiritEssence',
                '_totalSpiritEssence'
            ]),
            totalPerHero() {
                let heroesCount = this._heroes.length;
                if (heroesCount > 0) {
                    let amount = Math.round(this._totalSpiritEssence/heroesCount);
                    return amount.toLocaleString();
                }
                return 'N/A';
            },
            remainingPerHero() {
                let emptyHeroesCount = this._heroes.filter(function (hero) {
                    return hero.playerSpirit === null;
                }).length;
                if (emptyHeroesCount > 0) {
                    let amount = Math.round(this._availableSpiritEssence/emptyHeroesCount);
                    return amount.toLocaleString();
                }
                return 'N/A';
            }
        },
    }
</script>

<style scoped>

</style>

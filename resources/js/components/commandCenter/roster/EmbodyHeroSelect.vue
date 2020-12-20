<template>
    <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary"
                dark
                small
                :disabled="pending"
                v-bind="attrs"
                v-on="on"
            >
                {{ buttonText }}
            </v-btn>
        </template>
        <v-list>
            <v-list-item-group
                v-model="selectedHero"
                color="primary"
            >
                <v-list-item
                    v-for="(hero, index) in validHeroes"
                    @click="embodyHero(hero)"
                    :key="index"
                >
                    <v-list-item-title>{{ hero.name }}</v-list-item-title>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-menu>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import PlayerSpirit from "../../../models/PlayerSpirit";

    export default {
        name: "EmbodyHeroSelect",
        props: {
            playerSpirit: {
                type: PlayerSpirit,
                required: true
            }
        },
        data: () => ({
            selectedHero: null,
            pending: false
        }),

        computed: {
            ...mapGetters([
                '_heroes',
                '_heroRaceByID'
            ]),
            validHeroes() {
                let self = this;
                let playerSpiritPositionIDs = this.playerSpirit.playerGameLog.player.positionIDs;
                let embodied = this.embodiedHero;
                return this._heroes.filter(function (hero) {
                    if (embodied && hero.uuid === embodied.uuid) {
                        return false;
                    }
                    let heroPositionIDs = self._heroRaceByID(hero.heroRaceID).positionIDs;
                    let matchingIDs = playerSpiritPositionIDs.filter(playerPositionID => heroPositionIDs.includes(playerPositionID));
                    return matchingIDs.length > 0;
                });
            },
            embodiedHero() {
                return this._heroes.find(hero => hero.playerSpirit && hero.playerSpirit.uuid === this.playerSpirit.uuid);
            },
            buttonText() {
                return this.embodiedHero ? this.embodiedHero.name : 'Embody';
            }
        },
        methods: {
            ...mapActions([
                'addSpiritToHero'
            ]),
            async embodyHero (hero) {
                this.pending = true;

                await this.addSpiritToHero({
                    heroSlug: hero.slug,
                    spiritUuid: this.playerSpirit.uuid
                });

                this.selectedHero = null;
                this.pending = false;
            }

        }
    }
</script>

<style scoped>

</style>

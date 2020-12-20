<template>
    <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                :color="buttonColor"
                dark
                small
                :disabled="pending"
                v-bind="attrs"
                v-on="on"
            >
                <span class="d-inline-block text-truncate" style="max-width: 80px;">{{ buttonText }}</span>
            </v-btn>
        </template>
        <v-list>
            <v-list-item-group
                v-model="selectedHero"
                color="primary"
            >
                <v-list-item
                    v-for="(choice, index) in choices"
                    @click="handleClick(choice)"
                    :key="index"
                >
                    <v-list-item-title>
                        <span :style="'color: ' + choiceColor(choice)">{{ choice.name }}</span>
                    </v-list-item-title>
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
                '_heroRaceByID',
                '_availableSpiritEssence'
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
            choices() {
                let choices = this.validHeroes;
                // If the spirit embodied by a hero, we'll add a "remove" option
                let embodied = this.embodiedHero;
                if (embodied) {
                    choices.unshift({
                        'remove': true,
                        'name': 'Remove',
                        'slug': embodied.slug
                    });
                }
                return choices;
            },
            embodiedHero() {
                return this._heroes.find(hero => hero.playerSpirit && hero.playerSpirit.uuid === this.playerSpirit.uuid);
            },
            buttonText() {
                return this.embodiedHero ? this.embodiedHero.name : 'Embody';
            },
            buttonColor() {
                return this.embodiedHero ? 'info' : 'primary';
            }
        },
        methods: {
            ...mapActions([
                'addSpiritToHero',
                'removeSpiritFromHero'
            ]),
            async handleClick (choice) {
                this.pending = true;

                if (choice.remove) {
                    await this.removeSpiritFromHero({
                        heroSlug: choice.slug,
                        spiritUuid: this.playerSpirit.uuid
                    });
                } else {
                    let embodied = this.embodiedHero;
                    if (embodied) {
                        await this.removeSpiritFromHero({
                            heroSlug: embodied.slug,
                            spiritUuid: this.playerSpirit.uuid
                        });
                    }
                    await this.addSpiritToHero({
                        heroSlug: choice.slug,
                        spiritUuid: this.playerSpirit.uuid
                    });
                }
                this.selectedHero = null;
                this.pending = false;
            },
            choiceColor(choice) {
                if (choice.remove) {
                    return '#ffc747';
                }
                let spiritEssenceCost = this.embodiedHero ? 0 : this.playerSpirit.essenceCost;
                let essenceUsed = choice.playerSpirit ? choice.playerSpirit.essenceCost : 0;
                return this._availableSpiritEssence + essenceUsed >= spiritEssenceCost ? '#3fa391' : '#ff5252'
            }
        }
    }
</script>

<style scoped>

</style>

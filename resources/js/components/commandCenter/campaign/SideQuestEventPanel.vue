<template>
    <v-lazy
        v-model="isActive"
        :options="{
          threshold: .5
        }"
        min-height="10"
        transition="fade-transition"
        class="px-2"
    >
        <span v-if="description" :class="descriptionClasses">
            {{description}}
        </span>
    </v-lazy>
</template>

<script>
    import {mapGetters} from 'vuex';
    import CombatEvent from "../../../models/CombatEvent";

    export default {
        name: "SideQuestEventPanel",
        props: {
            sideQuestEvent: {
                type: CombatEvent,
                required: true
            },
            minions: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                isActive: false
            }
        },
        methods: {
            getMinionByMinionUuid(minionUuid) {
                return this.minions.find((minion) => minion.uuid === minionUuid);
            },
            getHero() {
                return this._heroByUuid(this.sideQuestEvent.data.combatHero.heroUuid);
            },
            getMinion() {
                return this.getMinionByMinionUuid(this.sideQuestEvent.data.combatMinion.minionUuid);
            },
            getDamage() {
                return this.sideQuestEvent.data.damage;;
            }
        },
        computed: {
            ...mapGetters([
                '_heroByUuid',
                '_squad'
            ]),
            description() {
                switch (this.sideQuestEvent.eventType) {
                    case 'hero-damages-minion':
                        return this.getHero().name + ' attacks ' + this.getMinion().name + ' for ' + this.getDamage() + ' damage ';
                    case 'minion-damages-hero':
                        return this.getMinion().name + ' attacks ' + this.getHero().name + ' for ' + this.getDamage() + ' damage ';
                    case 'hero-blocks-minion':
                        return this.getHero().name + ' blocks ' + this.getMinion().name;
                    case 'minion-blocks-hero':
                        return this.getMinion().name + ' blocks ' + this.getHero().name;
                    case 'hero-kills-minion':
                        return this.getHero().name + ' kills ' + this.getMinion().name + ' with ' + this.getDamage() + ' damage!';
                    case 'minion-kills-hero':
                        return this.getMinion().name + ' kills ' + this.getHero().name + ' with ' + this.getDamage() + ' damage!';
                    case 'side-quest-victory':
                        return this._squad.name + ' is victorious!';
                    case 'side-quest-defeat':
                        return this._squad.name + ' is defeated!';
                    default:
                        return null;
                }
            },
            descriptionClasses() {
                switch (this.sideQuestEvent.eventType) {
                    case 'hero-damages-minion':
                        return ['primary--text', 'text--lighten-1'];
                    case 'minion-damages-hero':
                        return ['accent--text', 'text--darken-1'];
                    case 'hero-blocks-minion':
                        return ['primary--text', 'text--lighten-2'];
                    case 'minion-blocks-hero':
                        return ['accent--text', 'text--lighten-1'];
                    case 'hero-kills-minion':
                        return ['success--text', 'text--lighten-1', 'title'];
                    case 'minion-kills-hero':
                        return ['error--text', 'text--lighten-1', 'title'];
                    case 'side-quest-victory':
                        return ['success--text', 'text--lighten-2', 'headline'];
                    case 'side-quest-defeat':
                        return ['error--text', 'text--lighten-2', 'headline'];
                    default:
                        return [];
                }
            }
        }
    }
</script>

<style scoped>

</style>

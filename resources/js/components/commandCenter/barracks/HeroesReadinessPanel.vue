<template>
    <SquadReadinessPanel name="heroes">
        <v-icon
            v-if="readiness === 'full'"
            x-large
            color="success"
        >
            check_circle
        </v-icon>
        <v-icon
            v-else-if="readiness === 'partial'"
            x-large
            color="accent"
        >
            error
        </v-icon>
        <v-icon
            v-else
            x-large
            color="error"
        >
            cancel
        </v-icon>
    </SquadReadinessPanel>
</template>

<script>
    import { mapGetters } from 'vuex'
    import SquadReadinessPanel from "./SquadReadinessPanel";
    export default {
        name: "HeroesReadinessPanel",
        components: {SquadReadinessPanel},
        computed: {
            ...mapGetters([
                '_heroes',
            ]),
            readiness() {
                let readyHeroes = this._heroes.filter(function (hero) {
                    return hero.readyForCombat();
                });
                if (readyHeroes.length === this._heroes.length) {
                    return 'full';
                }
                if (readyHeroes.length > 0) {
                    return 'partial';
                }
                return 'none';
            }
        }
    }
</script>

<style scoped>

</style>

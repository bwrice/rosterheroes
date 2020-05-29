<template>
    <SquadReadinessPanel name="heroes" :readiness="readiness"></SquadReadinessPanel>
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

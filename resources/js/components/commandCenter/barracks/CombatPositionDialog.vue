<template>
    <v-card class="pa-3">
        <v-card-title>
            <v-row justify="center">
                <span class="title" style="color: rgba(255, 255, 255, .85)">Change Combat Position</span>
            </v-row>
        </v-card-title>
        <v-row no-gutters>
            <v-col cols="8" offset="2" class="pa-2">
                <CombatPositionIcon :combat-position-id="selectedCombatPositionID"></CombatPositionIcon>
            </v-col>
            <v-col cols="8" offset="2">
                <v-row justify="center" align="center">
                    <v-select
                        v-model="selectedCombatPositionID"
                        :items="_combatPositions"
                        item-text="name" item-value="id"
                    ></v-select>
                </v-row>
            </v-col>
            <v-col cols="12">
                <v-row justify="center" align="center">
                    <v-btn color="error" class="mx-2" @click="emitClose">Cancel</v-btn>
                    <v-btn :disabled="disabled" @click="changeCombatPosition" color="primary" class="mx-2">Change Position</v-btn>
                </v-row>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
    import Hero from "../../../models/Hero";
    import CombatPositionIcon from "../global/CombatPositionIcon";
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    export default {
        name: "CombatPositionDialog",
        components: {CombatPositionIcon},
        created() {
            this.selectedCombatPositionID = this.hero.combatPositionID;
        },
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },
        data() {
            return {
                selectedCombatPositionID: 0,
                pending: false
            }
        },
        methods: {
            ...mapActions([
                'changeHeroCombatPosition'
            ]),
            emitClose() {
                this.$emit('close')
            },
            async changeCombatPosition() {
                this.pending = true;
                await this.changeHeroCombatPosition({
                    heroSlug: this.hero.slug,
                    combatPositionID: this.selectedCombatPositionID
                });
                this.pending = false;
                this.emitClose();
            }
        },
        computed: {
            ...mapGetters([
                '_combatPositions',
                '_combatPositionByID'
            ]),
            disabled() {
                return this.pending || (this.hero.combatPositionID === this.selectedCombatPositionID);
            },
        }
    }
</script>

<style scoped>

</style>

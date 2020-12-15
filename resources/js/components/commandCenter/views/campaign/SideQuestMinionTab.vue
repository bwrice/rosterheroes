<template>
    <BaseMinionTab :minion="minion">
        <v-row class="no-gutters">
            <v-col cols="12" class="px-1">
                <span class="subtitle-2 rh-op-75">ATTACKS:</span>

                <AttackPanel v-for="attack in minion.attacks" v-bind:key="attack.name"
                             :attack="attack"></AttackPanel>
            </v-col>
        </v-row>
    </BaseMinionTab>
</template>

<script>
    import Minion from "../../../../models/Minion";
    import AttackPanel from "../../global/AttackPanel";
    import BaseMinionTab from "../../campaign/BaseMinionTab";

    export default {
        name: "SideQuestMinionTab",
        components: {BaseMinionTab, AttackPanel},
        props: {
            minion: {
                type: Minion,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

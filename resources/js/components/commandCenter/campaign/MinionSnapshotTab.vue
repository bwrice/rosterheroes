<template>
    <BaseMinionTab :minion="minionSnapshot">
        <v-row class="no-gutters">
            <v-col cols="12" class="px-1">
                <span class="subtitle-2 rh-op-75">ATTACKS:</span>

                <AttackSnapshotPanel
                    v-for="(snapshot, uuid) in minionSnapshot.attackSnapshots"
                    :key="uuid"
                    :attack-snapshot="snapshot"
                >
                </AttackSnapshotPanel>
            </v-col>
        </v-row>
    </BaseMinionTab>
</template>

<script>
    import BaseMinionTab from "./BaseMinionTab";
    import MinionSnapshot from "../../../models/MinionSnapshot";
    import AttackSnapshotPanel from "./AttackSnapshotPanel";
    export default {
        name: "MinionSnapshotTab",
        components: {AttackSnapshotPanel, BaseMinionTab},
        props: {
            minionSnapshot: {
                type: MinionSnapshot,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

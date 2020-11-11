<template>
    <BaseAttackPanel :attack="attackSnapshot">

    </BaseAttackPanel>
</template>

<script>
    import AttackSnapshot from "../../../models/AttackSnapshot";
    import BaseAttackPanel from "../global/BaseAttackPanel";

    export default {
        name: "AttackSnapshotPanel",
        components: {BaseAttackPanel},
        props: {
            attackSnapshot: {
                type: AttackSnapshot,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

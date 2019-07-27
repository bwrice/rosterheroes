<template>
    <div>
        <PositionChip v-for="position in positions" v-bind:key="position.id" :position="position"></PositionChip>
    </div>
</template>

<script>
    import PositionChip from "./PositionChip";

    export default {
        name: "PositionChipList",
        components: {PositionChip},
        props: ['positions']
    }

</script>

<style scoped>
</style>
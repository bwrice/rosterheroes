<template>
    <PaginationBlock :items="spells">
        <template v-slot:default="slotProps">
            <SpellPanel :spell="slotProps.item">
                <template v-slot:after-boosts="panelProps">
                    <!-- nested scoped slots -->
                    <slot name="after-boosts" :spell="panelProps.spell">
                        <!-- slot:after-boosts -->
                    </slot>
                </template>
            </SpellPanel>
        </template>
    </PaginationBlock>
</template>

<script>
    import SpellPanel from "./SpellPanel";
    import PaginationBlock from "../global/PaginationBlock";
    export default {
        name: "SpellPanelIterator",
        components: {PaginationBlock, SpellPanel},
        props: {
            spells: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>

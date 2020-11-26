<template>
    <g>
        <path :d="fullArcPath" :fill="color" opacity="0.3" stroke="#333333"></path>
        <path :d="healthArcPath" :fill="color" stroke="#333333"></path>
    </g>
</template>

<script>
    import * as arcHelpers from "../../../../helpers/battlefieldArcHelpers";

    export default {
        name: "BattlefieldCombatPosition",
        props: {
            outerRadius: {
                type: Number,
                required: true
            },
            innerRadius: {
                type: Number,
                required: true
            },
            healthPercent: {
                type: Number,
                required: true
            },
            allySide: {
                type: Boolean,
                required: true
            },
            color: {
                type: String,
                required: true
            }
        },
        computed: {
            fullArcPath() {
                return arcHelpers.buildArcPath(this.outerRadius, this.innerRadius, 100, this.allySide);
            },
            healthArcPath() {
                return arcHelpers.buildArcPath(this.outerRadius, this.innerRadius, this.healthPercent, this.allySide);
            }
        }
    }

</script>

<style scoped>

</style>

<template>
    <g>
        <path :d="fullArcPath" :fill="color" opacity="0.3" stroke="#333333"></path>
        <path :d="healthArcPath" :fill="color" stroke="#333333"></path>
    </g>
</template>

<script>
    import TWEEN from "@tweenjs/tween.js";
    import * as arcHelpers from "../../../../helpers/battlefieldArcHelpers";

    export default {
        name: "BattlefieldArc",
        props: {
            combatPositionName: {
                type: String,
                required: true
            },
            healthPercent: {
                type: Number,
                required: true
            },
            allySide: {
                type: Boolean,
                required: true
            }
        },
        created() {
            tweenHealth(this.$data, this.healthPercent);
        },
        data() {
            return {
                tweenedHealthPercent: 0
            }
        },
        watch: {
            healthPercent(newValue) {
                tweenHealth(this.$data, newValue);
            }
        },
        computed: {
            fullArcPath() {
                return arcHelpers.buildArcPath(this.combatPositionName, 100, this.allySide);
            },
            healthArcPath() {
                return arcHelpers.buildArcPath(this.combatPositionName, this.tweenedHealthPercent, this.allySide);
            },
            color() {
                return arcHelpers.getColor({
                    combatPositionName: this.combatPositionName,
                    allySide: this.allySide
                })
            }
        }
    }

    function tweenHealth(data, newHealthPercent) {

        function animate () {
            if (TWEEN.update()) {
                requestAnimationFrame(animate)
            }
        }
        new TWEEN.Tween(data)
            .to({
                tweenedHealthPercent: newHealthPercent
            }, 1000)
            .easing(TWEEN.Easing.Quadratic.Out)
            .start();

        animate();
    }

</script>

<style scoped>

</style>

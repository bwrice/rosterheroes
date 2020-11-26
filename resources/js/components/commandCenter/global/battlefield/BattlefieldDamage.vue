<template>
    <g>
        <circle
            color="#fc7e23"
            :opacity="opacity"
            :cx="battlefieldDamageEvent.xPosition"
            :cy="battlefieldDamageEvent.yPosition"
            :r="radius"
        ></circle>
        <text :x="battlefieldDamageEvent.xPosition"
              :y="battlefieldDamageEvent.yPosition"
              text-anchor="middle"
              color="#fff"
              stroke="#fc7e23"
              stroke-width="1px"
              :font-size="radius/2"
        >
            {{battlefieldDamageEvent.damage}}
        </text>
    </g>
</template>

<script>
    import TWEEN from "@tweenjs/tween.js";

    export default {
        name: "BattlefieldDamage",
        props: {
            battlefieldDamageEvent: {
                type: Object,
                required: true
            }
        },
        watch: {
            battlefieldDamageEvent() {
                tweenRadius(this.$data, this.battlefieldDamageEvent.damage);
            }
        },
        created() {
            tweenRadius(this.$data, this.battlefieldDamageEvent.damage);
        },
        data() {
            return {
                radius: 0,
                opacity: .7
            }
        }
    }

    function tweenRadius(data, damage) {
        let magnitude = 20 + Math.sqrt(damage);
        data.radius = magnitude;
        data.opacity = 0.7;
        function animate () {
            if (TWEEN.update()) {
                requestAnimationFrame(animate)
            }
        }
        new TWEEN.Tween(data)
            .to({radius: magnitude * 3, opacity: 0}, 1000)
            .easing(TWEEN.Easing.Quadratic.Out)
            .start();

        animate();
    }
</script>

<style scoped>

</style>

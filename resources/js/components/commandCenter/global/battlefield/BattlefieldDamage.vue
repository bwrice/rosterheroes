<template>
    <g>
        <circle
            :color="color"
            :opacity="opacity"
            :cx="battlefieldDamageEvent.xPosition"
            :cy="battlefieldDamageEvent.yPosition"
            :r="radius"
        ></circle>
        <text :x="battlefieldDamageEvent.xPosition"
              :y="battlefieldDamageEvent.yPosition"
              text-anchor="middle"
              color="#fff"
              :stroke="color"
              stroke-width="1px"
              :font-size="radius/2"
        >
            {{battlefieldDamageEvent.damage}}
        </text>
    </g>
</template>

<script>
    import {mapGetters} from 'vuex';
    import TWEEN from "@tweenjs/tween.js";

    export default {
        name: "BattlefieldDamage",
        props: {
            battlefieldDamageEvent: {
                type: Object,
                required: true
            },
            color: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                radius: 0,
                opacity: .7
            }
        },
        watch: {
            battlefieldDamageEvent() {
                this.tweenRadius();
            }
        },
        created() {
            this.tweenRadius();
        },
        methods: {
            tweenRadius() {
                let magnitude = 20 + Math.sqrt(this.battlefieldDamageEvent.damage);
                this.radius = magnitude;
                this.opacity = 0.7;
                function animate () {
                    if (TWEEN.update()) {
                        requestAnimationFrame(animate)
                    }
                }
                new TWEEN.Tween(this.$data)
                    .to({radius: magnitude * 3, opacity: 0}, this._battlefieldSpeed)
                    .easing(TWEEN.Easing.Quadratic.Out)
                    .start();

                animate();
            }
        },
        computed: {
            ...mapGetters([
                '_battlefieldSpeed'
            ])
        }
    }
</script>

<style scoped>

</style>

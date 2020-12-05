<template>
    <g>
        <line
            v-if="showLine"
            :x1="x1"
            :y1="y1"
            :x2="x2"
            :y2="y2"
            :stroke="lineColor"
            stroke-width="5"
        >
        </line>
        <circle
            v-if="showDamage"
            :color="circleColor"
            :opacity="opacity"
            :cx="cx"
            :cy="cy"
            :r="radius"
        ></circle>
        <text :x="cx"
              :y="cy"
              v-if="showDamage"
              text-anchor="middle"
              alignment-baseline="central"
              color="#fff"
              :stroke="circleColor"
              stroke-width="1px"
              :font-size="radius/1.5"
              :opacity="opacity"
        >
            {{battlefieldDamageEvent.damage}}
        </text>
    </g>
</template>

<script>
    import {mapGetters} from 'vuex';
    import TWEEN from "@tweenjs/tween.js";
    import BattlefieldDamageEvent from "../../../../models/battlefield/BattlefieldDamageEvent";
    import {battlefieldLineMixin} from "../../../../mixins/battlefieldLineMixin";

    export default {
        name: "BattlefieldDamage",
        mixins: [battlefieldLineMixin],
        props: {
            battlefieldDamageEvent: {
                type: BattlefieldDamageEvent,
                required: true
            }
        },
        data() {
            return {
                cx: 0,
                cy: 0,
                radius: 0,
                opacity: 1,
                showDamage: false
            }
        },
        watch: {
            battlefieldDamageEvent() {
                this.renderAnimations();
            }
        },
        created() {
            this.renderAnimations();
        },
        methods: {
            async renderAnimations() {
                // set event for battlefield-line mixin
                this.battlefieldEvent = this.battlefieldDamageEvent;
                this.hideAll();

                let endCoords = this.battlefieldDamageEvent.getRandomCoords();

                this.renderLine(endCoords);

                await new Promise(resolve => setTimeout(resolve, this._battlefieldSpeed/2));

                this.showLine = false;

                this.renderCircle(endCoords);
            },
            renderCircle(coords) {
                this.cx = coords.x;
                this.cy = coords.y;
                let radius = 15 + this.battlefieldDamageEvent.magnitude;
                this.radius = radius;
                this.showDamage = true;
                this.opacity = 1;
                function animate () {
                    if (TWEEN.update()) {
                        requestAnimationFrame(animate)
                    }
                }
                new TWEEN.Tween(this.$data)
                    .to({radius: radius * 3, opacity: 0}, this._battlefieldSpeed/2)
                    .easing(TWEEN.Easing.Quadratic.Out)
                    .start();

                animate();
            },
            hideAll() {
                this.showDamage = false;
                this.showLine = false;
            }
        },
        computed: {
            ...mapGetters([
                '_battlefieldSpeed'
            ]),
            circleColor() {
                return this.battlefieldDamageEvent.allySide ? '#eb9800' : '#0088d6';
            }
        }
    }
</script>

<style scoped>

</style>

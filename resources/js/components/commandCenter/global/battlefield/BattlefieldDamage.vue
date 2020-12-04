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

    export default {
        name: "BattlefieldDamage",
        props: {
            battlefieldDamageEvent: {
                type: BattlefieldDamageEvent,
                required: true
            },
            sourceX: {
                type: Number,
                required: true
            },
            sourceY: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 0,
                cx: 0,
                cy: 0,
                radius: 0,
                opacity: 1,
                showDamage: false,
                showLine: false
            }
        },
        watch: {
            async battlefieldDamageEvent() {
                this.renderAnimations();
            }
        },
        async created() {
            this.renderAnimations();
        },
        methods: {
            async renderAnimations() {

                this.hideAll();

                let endCoords = this.battlefieldDamageEvent.getRandomCoords();

                this.renderLine(endCoords);

                await new Promise(resolve => setTimeout(resolve, this._battlefieldSpeed/2));

                this.showLine = false;

                this.renderCircle(endCoords);
            },
            renderLine(endCoords) {
                this.x1 = this.sourceX;
                this.y1 = this.sourceY;
                this.x2 = this.sourceX;
                this.y2 = this.sourceY;

                this.showLine = true;
                function animate () {
                    if (TWEEN.update()) {
                        requestAnimationFrame(animate)
                    }
                }
                new TWEEN.Tween(this.$data)
                    .to({x2: endCoords.x, y2: endCoords.y}, this._battlefieldSpeed/2)
                    .easing(TWEEN.Easing.Quadratic.In)
                    .start();

                new TWEEN.Tween(this.$data)
                    .to({x1: endCoords.x, y1: endCoords.y}, this._battlefieldSpeed/4)
                    .easing(TWEEN.Easing.Quadratic.In)
                    .delay(this._battlefieldSpeed/4)
                    .start();

                animate();
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
            lineColor() {
                return this.battlefieldDamageEvent.allySide ? '#ffcf4d' : '#03fce3';
            },
            circleColor() {
                return this.battlefieldDamageEvent.allySide ? '#eb9800' : '#0088d6';
            }
        }
    }
</script>

<style scoped>

</style>

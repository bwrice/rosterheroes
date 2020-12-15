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
        <g :transform="transform" v-if="showIcon">
            <path d="M12 4.942c1.827 1.105 3.474 1.6 5 1.833v7.76c0 1.606-.415 1.935-5 4.76v-14.353zm9-1.942v11.535c0
        4.603-3.203 5.804-9 9.465-5.797-3.661-9-4.862-9-9.465v-11.535c3.516 0 5.629-.134 9-3 3.371 2.866 5.484 3 9
        3zm-2 1.96c-2.446-.124-4.5-.611-7-2.416-2.5 1.805-4.554 2.292-7 2.416v9.575c0 3.042 1.69 3.83 7 7.107
        5.313-3.281 7-4.065 7-7.107v-9.575z">
            </path>
        </g>
    </g>
</template>

<script>
    import {mapGetters} from 'vuex';
    import TWEEN from '@tweenjs/tween.js';
    import BattlefieldBlockEvent from "../../../../models/battlefield/BattlefieldBlockEvent";
    import {battlefieldLineMixin} from "../../../../mixins/battlefieldLineMixin";

    export default {
        name: "BattlefieldBlock",
        mixins: [battlefieldLineMixin],
        props: {
            battlefieldBlockEvent: {
                type: BattlefieldBlockEvent,
                required: true
            }
        },
        data() {
            return {
                scale: 0,
                iconX: 0,
                iconY: 0,
                showIcon: false,
            }
        },
        created() {
            this.renderAnimations();
        },
        watch: {
            battlefieldBlockEvent() {
                this.renderAnimations();
            }
        },
        methods: {
            async renderAnimations() {
                
                // set event for battlefield-line mixin
                this.battlefieldEvent = this.battlefieldBlockEvent;
                this.hideAll();

                let endCoords = this.battlefieldBlockEvent.getRandomCoords();

                this.renderLine(endCoords);

                await new Promise(resolve => setTimeout(resolve, this._battlefieldSpeed/2));

                this.showLine = false;

                this.renderIcon(endCoords);
            },
            renderIcon(iconCoords) {
                this.scale = 1;
                this.iconX = iconCoords.x;
                this.iconY = iconCoords.y;
                this.showIcon = true;

                function animate () {
                    if (TWEEN.update()) {
                        requestAnimationFrame(animate)
                    }
                }
                new TWEEN.Tween(this.$data)
                    .to({
                        scale: 4,
                        iconX: iconCoords.x - 30,
                        iconY: iconCoords.y - 30
                    }, Math.floor(this._battlefieldSpeed * 2/5)) // slightly less than max duration (1/2)
                    .easing(TWEEN.Easing.Elastic.Out)
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
            transform() {
                return 'translate('+ this.iconX + ',' + this.iconY + ') scale(' + this.scale + ')';
            }
        }
    }
</script>

<style scoped>

</style>



import TWEEN from "@tweenjs/tween.js";
import BattlefieldEvent from "../models/battlefield/BattlefieldEvent";

export const battlefieldLineMixin = {


    data() {
        return {
            x1: 0,
            y1: 0,
            x2: 0,
            y2: 0,
            battlefieldEvent: new BattlefieldEvent({})
        }
    },
    methods: {
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
    },
    computed: {

        lineColor() {
            return this.battlefieldEvent.allySide ? '#ffcf4d' : '#03fce3';
        }
    }
};

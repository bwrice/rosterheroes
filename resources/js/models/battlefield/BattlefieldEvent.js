
import * as arcHelpers from "../../helpers/battlefieldArcHelpers";

export default class BattlefieldEvent {

    constructor({allySide, combatPositionName}) {
        this.allySide = allySide;
        this.combatPositionName = combatPositionName;
    }

    getRandomCoords() {

        let innerRadius = arcHelpers.getInnerRadius(this.combatPositionName);
        let outerRadius = arcHelpers.getOuterRadius(this.combatPositionName);
        let thickness = outerRadius - innerRadius;

        // Create a random radius and percent value to calculate x and y coords
        let radius = innerRadius + (thickness/4) + (Math.random() * (thickness/2));
        let percent = 5 + (Math.random() * 90);

        return {
            x: arcHelpers.getXPosition(radius, percent, this.allySide),
            y: arcHelpers.getYPosition(radius, percent, this.allySide)
        }
    }
}

export default class CombatPosition {
    constructor({id, name = '', attackerSVG = '', targetSVG = '', outerRadius = 1, innerRadius = 0}) {
        this.id = id;
        this.name = name;
        this.attackerSVG = attackerSVG;
        this.targetSVG = targetSVG;
        this.outerRadius = outerRadius;
        this.innerRadius = innerRadius;
    }
}

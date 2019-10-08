export default class CombatPosition {
    constructor({id, name = '', attackerSVG = '', targetSVG = ''}) {
        this.id = id;
        this.name = name;
        this.attackerSVG = attackerSVG;
        this.targetSVG = targetSVG;
    }
}

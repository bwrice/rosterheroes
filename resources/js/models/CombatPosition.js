import SvgIcon from "./SvgIcon";

export default class CombatPosition {
    constructor({name = '', icon}) {
        this.name = name;
        this.icon = icon ? new SvgIcon(icon) : SvgIcon({});
    }
}

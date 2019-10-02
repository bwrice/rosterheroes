import SvgIcon from "./SvgIcon";

export default class HeroRace {

    constructor({name = '', icon}) {
        this.name = name;
        this.icon = icon ? new SvgIcon(icon) : new SvgIcon({});
    }
}

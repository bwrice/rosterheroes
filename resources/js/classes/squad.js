import Hero from './hero';

export default class Squad {

    constructor(squad) {
        this._salary = squad.salary ? squad.salary : 0;
        this._name = squad.name ? squad.name : '';
        this._heroes = [];
        this.setHeroes(squad.heroes ? squad.heroes : []);
    }

    setHeroes(heroes) {
        let _heroes = this._heroes;
        heroes.forEach(function (hero) {
           _heroes.push(new Hero(hero));
        });
    }

    get salary() {
        return this._salary;
    }

    get availableSalary() {
        let available = this._salary;
        this._heroes.forEach(function(hero) {
            available -= hero.salaryUsed;
        });
        return available;
    }

    get name() {
        return this._name;
    }

    get heroes() {
        return this._heroes
    }
}
import HeroPost from './heroPost';

export default class Squad {

    constructor(squad) {
        this._salary = squad.salary ? squad.salary : 0;
        this._name = squad.name ? squad.name : '';
        this._heroPosts = squad.heroPosts ? squad.heroPosts : [];
        this._heroes = [];
    }

    get salary() {
        return this._salary;
    }

    get availableSalary() {
        let available = this._salary;
        this.heroes.forEach(function(hero) {
            available -= hero.salaryUsed;
        });
        return available;
    }

    get name() {
        return this._name;
    }

    get heroPosts() {
        let _heroPosts = [];
        this._heroPosts.forEach(function (heroPost) {
            _heroPosts.push(new HeroPost(heroPost))
        });
        return _heroPosts;
    }

    get heroes() {
        let _heroes = [];
        this.heroPosts.forEach(function (heroPost) {
            if (heroPost.hero) {
                _heroes.push(heroPost.hero);
            }
        });
        return _heroes;
    }
}
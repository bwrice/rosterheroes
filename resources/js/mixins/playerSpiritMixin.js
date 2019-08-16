
export const playerSpiritMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        playerSpiritPositions: function() {
            return this.playerSpirit.player.positions;
        },
        playerName: function() {
            return this.playerSpirit.player.full_name;
        },
        gameDescription: function() {
            return this.playerSpirit.game.description;
        },
        essenceCost: function() {
            return this.playerSpirit.essence_cost;
        },
        energy: function() {
            return this.playerSpirit.energy;
        }
    },
    watch: {
        //
    },
    methods: {
        //
    }
};

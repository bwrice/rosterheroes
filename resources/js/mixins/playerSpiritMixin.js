
import PlayerSpirit from '../models/PlayerSpirit';

export const playerSpiritMixin = {
    data: function() {
        return {
            //
        }
    },
    computed: {
        playerSpiritPositions: function() {
            // TODO filter out overlapping positions when new Hero Races are added
            return this.playerSpirit.player.positions;
        },
        playerName: function() {
            let playerSpirit = new PlayerSpirit(this.playerSpirit);
            return playerSpirit.playerName;
        },
        gameDescription: function() {
            let playerSpirit = new PlayerSpirit(this.playerSpirit);
            return playerSpirit.gameDescription;
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
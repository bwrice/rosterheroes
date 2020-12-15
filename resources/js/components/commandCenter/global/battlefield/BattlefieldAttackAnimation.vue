<template>
    <g>
        <g
            v-if="showIcon"
            :transform="transform"
        >
            <g
                transform="matrix(-0.00469153,0,0,0.00477198,60.025781,-0.01425514)">
                <path
                    fill="#ffffff"
                    d="m 655,12574 c -11,-2 -44,-9 -73,-15 -263,-53 -492,-280 -563,-560 -18,-70 -18,-268 0,-339 82,-323 355,-551 691,-576
                    l 88,-7 946,-946 946,-946 -940,-940 c -517,-517 -940,-944 -940,-948 0,-5 5,-5 10,-2 6,3 101,37 213,75 111,38 454,154 762,258
                    308,104 587,199 620,211 56,20 76,38 293,254 L 2940,8325 4015,7250 5090,6175 2790,3875 490,1575 460,1405 C 383,958 230,38
                    230,22 c 0,-16 74,-6 786,114 l 786,131 2299,2299 2299,2299 2303,-2301 2302,-2302 785,-131 c 431,-71 785,-129 786,-128 1,1
                    -57,356 -130,790 l -131,787 -2300,2300 -2300,2300 1072,1072 1073,1073 232,-232 233,-233 150,-51 c 83,-28 400,-135 705,-239
                    305,-103 616,-209 690,-234 l 135,-46 -948,947 -947,948 947,946 948,947 87,6 c 181,14 338,81 463,198 111,104 181,221 226,378
                    18,61 18,271 0,339 -72,280 -282,490 -562,562 -32,8 -103,14 -174,14 -97,0 -134,-5 -195,-24 -220,-68 -390,-215 -481,-417
                    -36,-78 -56,-163 -65,-269 l -6,-80 -947,-947 -946,-948 -942,942 c -519,519 -943,940 -943,937 0,-6 20,-64 359,-1069 l
                    202,-595 234,-235 235,-235 L 7477,8562 6405,7490 5330,8565 4255,9640 l 232,232 233,233 39,115 c 21,63 125,367 229,675
                    105,308 213,627 241,709 28,82 51,154 51,160 0,6 -424,-413 -943,-932 l -942,-942 -947,948 -946,947 -7,84 c -16,205 -81,348
                    -220,486 -144,144 -298,213 -495,220 -58,2 -114,2 -125,-1 z">
                </path>
            </g>
        </g>
        <g>
            <BattlefieldBlock
                v-for="(blockEvent, id) in battlefieldAttack.battlefieldBlocks"
                :key="id"
                :battlefield-block-event="blockEvent"
                :source-x="sourceX"
                :source-y="sourceY"
            ></BattlefieldBlock>
        </g>
        <g>
            <BattlefieldDamage
                v-for="(damageEvent, id) in battlefieldAttack.battlefieldDamages"
                :key="id"
                :battlefield-damage-event="damageEvent"
                :source-x="sourceX"
                :source-y="sourceY"
            ></BattlefieldDamage>
        </g>
    </g>
</template>

<script>
    import BattlefieldAttackEvent from "../../../../models/battlefield/BattlefieldAttackEvent";
    import BattlefieldDamage from "./BattlefieldDamage";
    import BattlefieldBlock from "./BattlefieldBlock";

    export default {
        name: "BattlefieldAttackAnimation",
        components: {BattlefieldBlock, BattlefieldDamage},
        props: {
            battlefieldAttack: {
                type: BattlefieldAttackEvent,
                required: true
            }
        },
        data() {
            return {
                sourceX: 0,
                sourceY: 0,
                iconX: 0,
                iconY: 0,
                scale: 0,
                showIcon: false
            }
        },
        created() {
            this.boot();
        },
        watch: {
            battlefieldAttack() {
                this.boot();
            }
        },
        methods: {
            boot() {
                this.setRandomSourceCoords();
                this.scale = 1;
                this.showIcon = true;
            },
            setRandomSourceCoords() {
                let coords = this.battlefieldAttack.getRandomCoords();
                this.sourceX = coords.x;
                this.iconX = coords.x - 30;
                this.sourceY = coords.y;
                this.iconY = coords.y - 30;
            }
        },
        computed: {
            transform() {
                return 'translate('+ this.iconX + ',' + this.iconY + ') scale(' + this.scale + ')';
            }
        }
    }
</script>

<style scoped>

</style>

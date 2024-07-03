const audio = {
    Map: new Howl({
        src: '../images/game_assets/music/town_map.mp3',
        html5: true,
        volume: 0.3,
        loop: true
    }), 
    Battle: new Howl({
        src: '../images/game_assets/music/battle.mp3',
        html5: true,
        volume: 0.3,
        loop: true
    }),
    Attack: new Howl({
        src: '../images/game_assets/music/Tackle.mp3',
        html5: true,
        volume: 0.3
    }),
    Heal: new Howl({
        src: '../images/game_assets/music/potion.mp3',
        html5: true,
        volume: 0.3
    })
}
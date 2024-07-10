// import { setupCanvas } from './utils.js';
// import {nextDialog, closeDialog, nextHelp, closeHelpDialog} from './dialog.js'
// import { createMap } from './utils.js';


// document.addEventListener('DOMContentLoaded', () => {
//     setupCanvas();
//     nextDialog();
//     createMap();
//     closeDialog();
//     nextHelp();
//     closeHelpDialog()
   
//   });
  
//   window.addEventListener('keydown', handlePlayerMovement);

import { collisions } from './collision.js';
import { grassEncounter } from './grassEncounter.js';
import { Boundary } from './Boundary.js'
import { Sprite } from './Sprite.js'

const canvas = document.querySelector('canvas')
const ctx = canvas.getContext('2d')
canvas.width = 1430
canvas.height = 860

const collisionMap = []
for (let i = 0; i < collisions.length; i+=84){
collisionMap.push(collisions.slice(i, 84 + i))
}

const grassEncounterMap = []
for (let i = 0; i < grassEncounter.length; i+=84){
grassEncounterMap.push(grassEncounter.slice(i, 84 + i))
}

const boundaries = []
const offset = {
  x:-210,
  y:-900
}

collisionMap.forEach((row, i) => {
  row.forEach((symbol, j) => {
    if(symbol === 1301)
    boundaries.push(new Boundary({position: {
      x: j * Boundary.width + offset.x,
      y: i * Boundary.height + offset.y
    }
    }))
  })
})

const battleZones = []

grassEncounterMap.forEach((row, i) => {
  row.forEach((symbol, j) => {
    if(symbol === 1301)
    battleZones.push(new Boundary({position: {
      x: j * Boundary.width + offset.x,
      y: i * Boundary.height + offset.y
    }
    }))
  })
})

let pokemonName1 = ""; 
let pokemonName2 = ""; 
const image = new Image()
image.src = '../images/journey.png'

const foreImage = new Image()
foreImage.src = '../images/foreground.png'

const playerImage = new Image()
playerImage.src = '../images/game_assets/graphics/characters/player.png'

const player = new Sprite({
  position: {
    x: canvas.width / 2 - 512 / 4 / 2,
    y: canvas.height / 2 - 350 / 2
  }, 
  image: playerImage,

  frames: {
    max: 4
  },
  sprites: [0, 1, 2, 3]
})

const background = new Sprite({
  position: { x: offset.x, 
              y: offset.y
},
image: image
})

const foreground = new Sprite({
  position: { x: offset.x, 
              y: offset.y
},
image: foreImage
})

const battleBackgroundImage = new Image()
battleBackgroundImage.src = '../images/game_assets/backgrounds/background1.png'
const battleBackground = new Sprite({position: {
  x: 0,
  y: 0
},
image: battleBackgroundImage
})

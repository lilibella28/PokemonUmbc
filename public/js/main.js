

import { collisions } from './collision.js';
import { grassEncounter } from './grassEncounter.js';
import { Boundary } from './Boundary.js'
import{image, foreImage, playerImage, player, background, foreground, battleBackgroundImage, battleBackground} from './SpriteImage.js'
import{nextDialog, closeDialog,nextHelp, closeHelpDialog} from './dialog.js'

const NextDialogId =document.getElementById('nextDialog')
const CloseDialogId =document.getElementById('closeDialog')
const NextHelpId =document.getElementById('nextHelp')
const CloseHelpDialogId =document.getElementById('closeHelpDialog')
NextDialogId.addEventListener('click', nextDialog)
CloseDialogId.addEventListener('click', closeDialog)
NextHelpId.addEventListener('click', nextHelp)
CloseHelpDialogId.addEventListener('click', closeHelpDialog)

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



let battleAnimationId 

function animateBattle(){
  battleAnimationId = window.requestAnimationFrame(animateBattle)
  battleBackground.draw(canvas.width, canvas.height)
  document.querySelector('#userInterface').style.display = 'block'
  pokemon1.draw(150, 150)
  pokemon2.flip(true);
  pokemon2.draw(150, 150)
  updatePokemonNames();
  updateHealthBars();
}
animateBattle()
import { Sprite } from './Sprite.js'

export const image = new Image()
image.src = '../images/journey.png'

export const foreImage = new Image()
foreImage.src = '../images/foreground.png'

export const playerImage = new Image()
playerImage.src = '../images/game_assets/graphics/characters/player.png'

export const player = new Sprite({
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

export const background = new Sprite({
  position: { x: offset.x, 
            y: offset.y
},
image: image
})

export const foreground = new Sprite({
  position: { x: offset.x, 
              y: offset.y
},
image: foreImage})

export const battleBackgroundImage = new Image()
battleBackgroundImage.src = '../images/game_assets/backgrounds/background1.png'
const battleBackground = new Sprite({position: {
  x: 0,
  y: 0
},
image: battleBackgroundImage
})
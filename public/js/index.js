const canvas = document.querySelector('canvas')
const ctx = canvas.getContext('2d')
console.log(gsap);

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

const keys = {
  w: {
    pressed: false
  },
  a: {
    pressed: false
  },
  s: {
    pressed: false
  },
  d: {
    pressed: false
  },
  ArrowUp: {
    pressed: false
  },
  ArrowLeft: {
    pressed: false
  },
  ArrowDown: {
    pressed: false
  },
  ArrowRight: {
    pressed: false
  }
}

//These are just test sprites, you are going to have to modify this to get the pokemon images somehow
const monImage = new Image()
monImage.src = '../../proj3_images/1st_generation/004Charmander.png'
const pokemon1 = new Sprite({
  position: {
    x: 1000, 
    y: 200
  }, 
  image:monImage
})

const mon2Image = new Image()
mon2Image.src = '../../proj3_images/1st_generation/025Pikachu.png'
const pokemon2 = new Sprite({
  position: {
    x: 400, 
    y: 500
  }, 
  image:mon2Image
})

// Dummy data for testing, need to change this with database calls and other stuff
//Also note that you need to add functions to add functionalitites to the buttons
//These are all presets that need to be replaced 
const attacks = [
  { name: 'Tackle', power: 40 },
  { name: 'Thunderbolt', power: 90 },
  { name: 'Fire Blast', power: 110 },
  { name: 'Water Gun', power: 50 }
];

const items = [
  { name: 'Potion', quantity: 5 },
  { name: 'Super Potion', quantity: 3 },
  { name: 'Revive', quantity: 2 }
];

const pokemonTeam = [
  { name: 'Pikachu', level: 25 },
  { name: 'Charizard', level: 36 },
  { name: 'Blastoise', level: 34 },
  { name: 'Venusaur', level: 32 }
];

// Try to create a function to get the pokemon name from the database and call the funciton below to get the name to show
const pokemonName1 = "Charmander"; 
const pokemonName2 = "Pikachu"; 

// Function to update Pokémon names dynamically
function updatePokemonNames() {
    document.getElementById('pokemon-name-1').innerText = pokemonName1;
    document.getElementById('pokemon-name-2').innerText = pokemonName2;
}

// Call the function to update Pokémon names initially
 //call function to get it, 


// Function to show attack options
function showAttackOptions() {
  document.getElementById('attack-options').classList.add('active');
  document.getElementById('bag-items').classList.remove('active');
  document.getElementById('team-pokemon').classList.remove('active');

  // Clear previous list
  const attackList = document.getElementById('attack-list');
  attackList.innerHTML = '';

  // Populate attack list
  attacks.forEach(attack => {
    const button = document.createElement('button');
    button.className = 'battle-button';
    button.textContent = `${attack.name} - Power: ${attack.power}`;
    button.onclick = () => {
      // Implement the attack action here
      console.log(`Used ${attack.name}`);
    };
    attackList.appendChild(button);
  });
}

// Function to show bag items
function showBagItems() {
  document.getElementById('attack-options').classList.remove('active');
  document.getElementById('bag-items').classList.add('active');
  document.getElementById('team-pokemon').classList.remove('active');

  // Clear previous list
  const itemList = document.getElementById('item-list');
  itemList.innerHTML = '';

  // Populate item list
  items.forEach(item => {
    const button = document.createElement('button');
    button.className = 'battle-button';
    button.textContent = `${item.name} - Quantity: ${item.quantity}`;
    button.onclick = () => {
      // Implement the item usage action here
      console.log(`Used ${item.name}`);
    };
    itemList.appendChild(button);
  });
}

// Function to show team list
function showTeamList() {
  document.getElementById('attack-options').classList.remove('active');
  document.getElementById('bag-items').classList.remove('active');
  document.getElementById('team-pokemon').classList.add('active');

  // Clear previous list
  const pokemonList = document.getElementById('pokemon-list');
  pokemonList.innerHTML = '';

  // Populate Pokemon list
  pokemonTeam.forEach(pokemon => {
    const button = document.createElement('button');
    button.className = 'battle-button';
    button.textContent = `${pokemon.name} - Level: ${pokemon.level}`;
    button.onclick = () => {
      // Implement the Pokémon switching action here
      console.log(`Switched to ${pokemon.name}`);
    };
    pokemonList.appendChild(button);
  });
}

// Function to escape battle (dummy function)
function escapeBattle() {
  console.log('Escaped from battle');
}

const moving = [background, ...boundaries, foreground, ...battleZones]


function rectCollide({rect1, rect2}){
  return(
    rect1.position.x + rect1.width - 30 >= rect2.position.x &&
     rect1.position.x + 30 <= rect2.position.x + rect2.width &&
     rect1.position.y + 10 <= rect2.position.y + rect2.height &&
     rect1.position.y + rect1.height - 10 >= rect2.position.y
  )
}

function rectOverlap({rect1, rect2}){
  return(
    rect1.position.x + rect1.width - 60 >= rect2.position.x &&
     rect1.position.x + 60 <= rect2.position.x + rect2.width &&
     rect1.position.y + 110 <= rect2.position.y + rect2.height &&
     rect1.position.y + rect1.height - 40 >= rect2.position.y
    )
}

let move = true
player.moves = false
const battle = {initiated: false}

function animate(){
  const animationId = window.requestAnimationFrame(animate)
  background.draw()
  boundaries.forEach(boundary => {
    boundary.draw()

    if(
      rectCollide({
        rect1: player,
        rect2: boundary
    })
  )
    {
      console.log('colliding')
    } 

  })
  battleZones.forEach(battleZones => {
    battleZones.draw();
  })
  player.draw()
  foreground.draw();
if(battle.initiated) return

  if(keys.w.pressed || keys.ArrowUp.pressed || 
     keys.a.pressed || keys.ArrowLeft.pressed ||
     keys.s.pressed || keys.ArrowDown.pressed ||
     keys.d.pressed || keys.ArrowRight.pressed) {
      
      for (let i = 0; i < battleZones.length; i++){
        const battleZone = battleZones[i]
        if(
          rectOverlap({
            rect1: player,
            rect2: battleZone
        }) && Math.random() < 0.01
      )
        {
          console.log('activate battle')
          //deactivate current animation loop
          window.cancelAnimationFrame(animationId)
          battleZone.initiated = true
          gsap.to('#overlap', {
            opacity: 1,
            repeat: 3,
            yoyo: true,
            duration: 0.4,
            onComplete(){
              gsap.to('overlap', {
                opacity: 1,
                duration: 0.4,
                onComplete() {   
              //activate new animation loop
              animateBattle()
              gsap.to('overlap', {
                opacity: 0,
                duration: 0.4
              })
                }
              })

            }
          })
          break
        } 
      }
     }


  if((keys.w.pressed && prevKey === 'w') || (keys.ArrowUp.pressed && prevKey === 'ArrowUp')){
    player.currentSprite = 3
    player.moves = true
    for (let i = 0; i < boundaries.length; i++){
      const boundary = boundaries[i]
      if(
        rectCollide({
          rect1: player,
          rect2: {...boundary, position: {
            x: boundary.position.x,
            y: boundary.position.y + 3
          }}
      })
    )
      {
        console.log('colliding')
        move = false
        break
      }
    }

    

    if(move)
    moving.forEach(moving => {moving.position.y += 3})
  }
  else if((keys.a.pressed && prevKey === 'a') || (keys.ArrowLeft.pressed && prevKey === 'ArrowLeft')){
    player.currentSprite = 1
    player.moves = true
    for (let i = 0; i < boundaries.length; i++){
      const boundary = boundaries[i]
      if(
        rectCollide({
          rect1: player,
          rect2: {...boundary, position: {
            x: boundary.position.x + 3,
            y: boundary.position.y 
          }}
      })
    )
      {
        console.log('colliding')
        move = false
        break
      }
    }
    if(move)
    moving.forEach(moving => {moving.position.x += 3})
  }
  else if((keys.s.pressed && prevKey === 's') || (keys.ArrowDown.pressed && prevKey === 'ArrowDown')){
    player.currentSprite = 0
    player.moves = true
    for (let i = 0; i < boundaries.length; i++){
      const boundary = boundaries[i]
      if(
        rectCollide({
          rect1: player,
          rect2: {...boundary, position: {
            x: boundary.position.x,
            y: boundary.position.y - 3 
          }}
      })
    )
      {
        console.log('colliding')
        move = false
        break
      }
    }
    if(move) 
    moving.forEach(moving => {moving.position.y -= 3})
  }
  else if((keys.d.pressed && prevKey === 'd') || (keys.ArrowRight.pressed && prevKey === 'ArrowRight')){
    player.currentSprite = 2
    player.moves = true
    for (let i = 0; i < boundaries.length; i++){
      const boundary = boundaries[i]
      if(
        rectCollide({
          rect1: player,
          rect2: {...boundary, position: {
            x: boundary.position.x - 3,
            y: boundary.position.y 
          }}
      })
    )
      {
        console.log('colliding')
        move = false
        break
      }
    }
    if(move)
    moving.forEach(moving => {moving.position.x -= 3})
  }
}
//currently turned off to code battle scenario, uncomment to get the overworld area
//animate();

const battleBackgroundImage = new Image()
battleBackgroundImage.src = '../images/game_assets/backgrounds/background1.png'
const battleBackground = new Sprite({position: {
  x: 0,
  y: 0
},
image: battleBackgroundImage
})

//Here is the animate battle function, this is where all the background and sprites are being drawn to, make functions if necessary
// for more implementation
let battleAnimationId 
function animateBattle(){
  battleAnimationId = window.requestAnimationFrame(animateBattle)
  battleBackground.draw(canvas.width, canvas.height)
  pokemon1.draw(150, 150)
  pokemon2.flip(true);
  pokemon2.draw(150, 150)
  updatePokemonNames();
  console.log('animating battle')
}

animateBattle();


let prevKey = ''
window.addEventListener('keydown', (e) => {
  switch(e.key){
    case 'w':
      keys.w.pressed = true
      prevKey = 'w'
      break
    case 'ArrowUp':
      keys.ArrowUp.pressed = true
      prevKey = 'ArrowUp'
      break
    case 'a':
      keys.a.pressed = true
      prevKey = 'a'
      break
    case 'ArrowLeft':
      keys.ArrowLeft.pressed = true
      prevKey = 'ArrowLeft'
      break
    case 's':
      keys.s.pressed = true
      prevKey = 's'
      break
    case 'ArrowDown':
      keys.ArrowDown.pressed = true
      prevKey = 'ArrowDown'
      break
    case 'd':
      keys.d.pressed = true
      prevKey = 'd'
      break
    case 'ArrowRight':
      keys.ArrowRight.pressed = true
      prevKey = 'ArrowRight'
      break
  }
})

window.addEventListener('keyup', (e) => {
  switch(e.key){
    case 'w':
      keys.w.pressed = false
      break
    case 'ArrowUp':
      keys.ArrowUp.pressed = false
      break
    case 'a':
      keys.a.pressed = false
      break
    case 'ArrowLeft':
      keys.ArrowLeft.pressed = false
      break
    case 's':
      keys.s.pressed = false
      break
    case 'ArrowDown':
      keys.ArrowDown.pressed = false
      break
    case 'd':
      keys.d.pressed = false
      break
    case 'ArrowRight':
      keys.ArrowRight.pressed = false
      break
  }
})
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

function fetchPokemonData() {
  fetch('http://localhost/PokemonUmbc/public/images/pokemon_assest.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json(); 
      })
      .then(data => {
        // Only for testing purpose delete it. 
        random_pokemon = Math.floor(Math.random() * data.length) + 1;
        let pokemonId = data[random_pokemon]?.ID
        console.log(pokemonId)
  

      })
      .catch(error => console.error('Error fetching or parsing data:', error));
}



const attacks = [
  { name: 'Tackle', power: 40 },
  { name: 'Thunderbolt', power: 90 },
  { name: 'Fire Blast', power: 110 },
  { name: 'Water Gun', power: 50 }
];

const items = [
  { name: 'pokebal', quantity: 1 },
  { name: 'greatball', quantity: 1.5 },
  { name: 'ultraball', quantity: 2 },
  { name: 'quickball', quantity: 1 },
  { name: 'netball', quantity: 2 },
  { name: 'fastball', quantity: 1 }
];


let pokemonTeam = [];

function fetchUsersTeams() {
  fetch('http://localhost/PokemonUmbc/public/images/userTeams_database.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json(); 
      })
      .then(data => {
        pokemonTeam = data.map(pokemon => ({
          name: pokemon.Name,
          level: pokemon.Level
        }));
        random_pokemon = Math.floor(Math.random() * data.length) + 1;
        let pokemonId = data[random_pokemon]?.ID
        let datas = fetchPokemonData()
        console.log(datas)
          alert(pokemonId)
          alert(datas)
      

      })
      .catch(error => console.error('Error fetching or parsing data:', error));
}




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


const userId = 1;
let wildPokemonID = Math.floor(Math.random() * 500) + 1; // Example wild Pokémon ID, dynamically set this based on encounter
let level = Math.floor(Math.random() * 100) + 1; // Example level, dynamically set this as needed

        function capture() {
            fetch('http://localhost/PokemonUmbc/capture/capture.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'addToParty',
                    ID: wildPokemonID,
                    userId: userId,
                    level: level
                })
            })
            .then(response => response.text())
            .then(message => {
             wildPokemonID = Math.floor(Math.random() * 500) + 1; //adding random pokemon 
             level = Math.floor(Math.random() * 100) + 1; //adding pokemon
              fetchUsersTeams()
                alert(message);
            });}

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
      capture()
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
  gsap.to('#overlap', {
    opacity: 1,
    onComplete: () => {
      cancelAnimationFrame(battleAnimationId)
      animate()
      document.querySelector('#userInterface').style.display = 'none'
      gsap.to('#overlap', {
        opacity: 0
      })
    }
  })
  console.log('Escaped from battle');
}

const moving = [background, ...boundaries, foreground, ...battleZones]


function rectCollide({rect1, rect2}){
  return(
    rect1.position.x + rect1.width - 30 >= rect2.position.x &&
     rect1.position.x + 30 <= rect2.position.x + rect2.width &&
     rect1.position.y + 78 <= rect2.position.y + rect2.height &&
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
  move = true
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
              gsap.to('#overlap', {
                opacity: 1,
                duration: 0.8,
                onComplete() {   
              //activate new animation loop
              animateBattle()
              gsap.to('#overlap', {
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
    moving.forEach((moving) => {moving.position.y += 3})
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
    moving.forEach((moving) => {moving.position.x += 3})
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
    moving.forEach((moving) => {moving.position.y -= 3})
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
    moving.forEach((moving) => {moving.position.x -= 3})
  }
}
//currently turned off to code battle scenario, uncomment to get the overworld area
animate();

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
  document.querySelector('#userInterface').style.display = 'block'
  pokemon1.draw(150, 150)
  pokemon2.flip(true);
  pokemon2.draw(150, 150)
  updatePokemonNames();
  console.log('animating battle')
}

//animateBattle();


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
      player.moves = false
      break
    case 'ArrowUp':
      keys.ArrowUp.pressed = false
      player.moves = false
      break
    case 'a':
      keys.a.pressed = false
      player.moves = false
      break
    case 'ArrowLeft':
      keys.ArrowLeft.pressed = false
      player.moves = false
      break
    case 's':
      keys.s.pressed = false
      player.moves = false
      break
    case 'ArrowDown':
      keys.ArrowDown.pressed = false
      player.moves = false
      break
    case 'd':
      keys.d.pressed = false
      player.moves = false
      break
    case 'ArrowRight':
      keys.ArrowRight.pressed = false
      player.moves = false
      break
  }
})

document.getElementById('npc').addEventListener('click', function () {
  document.getElementById('dialog-box').style.display = 'block';
});

let dialogIndex = 0;
const dialogText = [
  "Welcome, traveler, to the Mystical Forest of Eldoria! This ancient woodland is teeming with hidden wonders and powerful Pokémon waiting to be discovered.",
  "The forest's dense trees and hidden cabins are home to many secrets and mysterious creatures.",
  "Legend has it that deep within the forest lies the Sacred Grove, where the mythical Pokémon, Lumina, is said to reside.",
  "Lumina holds the power to control the elements and bring balance to our world. However, a dark force has emerged from the depths of the ocean, threatening to disrupt this harmony.",
  "To protect Eldoria, you must journey through the forest, challenging the Pokémon guardians that dwell within.",
  "But beware, for the final battle awaits you on the shores of the Enchanted Ocean.",
  "There, you will face the malevolent Sea Serpent, Tempestus, whose fury can summon raging storms and monstrous waves.",
  "Gather your courage, train your Pokémon, and uncover the secrets of Eldoria.",
  "Only then can you restore peace to our land and earn the right to meet Lumina.",
  "Your adventure begins now!"
];

function nextDialog() {
  dialogIndex++;
  if (dialogIndex < dialogText.length) {
      document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
  } else {
      closeDialog();
  }
}

function closeDialog() {
  document.getElementById('dialog-box').style.display = 'none';
  dialogIndex = 0;
}

gsap.to('#npc', {
  opacity: 0.5,
  yoyo: true,
  repeat: -1,
  duration: 0.5
});

window.addEventListener('load', function() {
  document.getElementById('dialog-box').style.display = 'block';
  document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
});

window.addEventListener('keydown', function(e) {
  if (e.key.toLowerCase() === 'h') {
      document.getElementById('dialog-box').style.display = 'block';
      document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
  }
});

window.onload = fetchPokemonData;



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
monImage.src = "../../proj3_images/1st_generation/Primeape.png"


const pokemon1 = new Sprite({
  position: {
    x: 1000, 
    y: 200
  }, 
  image:monImage
})

const mon2Image = new Image()
mon2Image.src = '../../proj3_images/1st_generation/Pikachu.png'

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

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  pokemon1.draw();
  pokemon2.draw();
  requestAnimationFrame(animate);
}

function shakeImages() {
  pokemon1.shake(20); // Shake for 20 frames
  pokemon2.shake(20); // Shake for 20 frames
}

function battleSequence() {
  // Example sequence: Pokemon1 attacks Pokemon2, then Pokemon2 attacks Pokemon1
  pokemon1.attack(pokemon2, -50, 10); // Pokemon1 attacks Pokemon2

  setTimeout(() => {
    pokemon2.attack(pokemon1, 50, 10); // Pokemon2 attacks Pokemon1 after a delay
  }, 1000); // Adjust delay for a better sequence timing
}


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
        randomPokemonOne = Math.floor(Math.random() * data.length) + 1;
        randomPokemonTwo = Math.floor(Math.random() * data.length) + 1;
        let pokemon1NameData = data[randomPokemonOne ]?.Name
        let pokemon2NameData = data[randomPokemonTwo]?.Name
        pokemonName1 = pokemon1NameData
        pokemonName2 = pokemon2NameData
        monImage.src = `../../proj3_images/1st_generation/${pokemonName1}.png`
        mon2Image.src = `../../proj3_images/1st_generation/${pokemonName2}.png`

      })
      .catch(error => console.error('Error fetching or parsing data:', error));
}



const attacks = [
  { name: 'Tackle', power: 20 },
  { name: 'Thundershock', power: 25 },
  { name: 'Electro ball', power: 35 },
  { name: 'Scratch', power: 15 }
];

const charAttacks = [
  { name: 'Scratch', power: 15 },
  { name: 'Ember', power: 20 },
  { name: 'Flamethrower', power: 40 },
  { name: 'Tackle', power: 20 }
];

const items = [
  { name: 'Potion', quantity: 5 },
  { name: 'Super Potion', quantity: 3 },
  { name: 'pokebal', quantity: 1 },
  { name: 'greatball', quantity: 1.5 },
  { name: 'ultraball', quantity: 2 },
  { name: 'quickball', quantity: 1 },
  { name: 'netball', quantity: 2 },
  { name: 'fastball', quantity: 1 }
];

const myUserID = Math.random() * 1000;

function checkType(type, pokemon) {
  return pokemon.getAttribute('monType1') === type || pokemon.getAttribute('monType2') === type;
}

function calcCatchRate(currHP, maxHP, ball, wildMonData, turn) {
  if (ball.name === "quickball" && turn === 1) {
       alert("quick draw confirmed");
    ball.quantity = 4;
  } else if (ball.name === "netball" && (checkType("Water", wildMonData) || checkType("Bug", wildMonData))) {
    alert("net confirmed");
    ball.quantity = 3.5;
  } else if (ball.name === "fastball" && parseInt(wildMonData.getAttribute('monSpeed')) >= 100) {
    alert("fast confirmed");
    ball.quantity = 4;
  }
  let numerator = 1 + ((maxHP * 3) - (currHP * 2)) * ball.quantity;
  let denominator = maxHP * 3;
  return numerator / denominator;
}

function catchMon(ball, pokemon, turn, currHP, maxHP, level) {
  let isCaught = false;
  let catchRate = calcCatchRate(currHP, maxHP, ball, pokemon, turn);
  let capture = Math.random();
  if (capture < catchRate) {
     alert("We caught him!!!");
    isCaught = true;
    addToUser(pokemon, level);
  } else if (capture < catchRate * 0.2) {
    alert("Aww so close");
    isCaught = false;
  } else {
    alert("The pokemon broke free!!!");
    isCaught = false;
  }
  return isCaught;
}

async function addToUser(pokemon, level) {
  const monID = pokemon.value;
  const userId = myUserID;
  const response = await fetch('http://localhost/PokemonUmbc/src/capture/capture.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      action: 'addToParty',
      ID: monID,
      userId: userId,
      level: level
    })
  });
  const result = await response.text();
  // alert( result);
}

function getBallInfo(ballName) {
  return items.find(item => item.name === ballName);
}

function handleBagItemClick(item) {
  const wildPokemon = { getAttribute: () => "Water" }; 
  const currHP = 50;
  const maxHP = 100; 
  const level = 10; 
  const turn = 1; 

  let ball = getBallInfo(item.name);
  if (!ball) {
    console.error('Invalid ball name:', item.name);
    return;
  }
  ball.rateModifier = item.quantity; // Use the quantity as the rateModifier
  let isCaught = catchMon(ball, wildPokemon, turn, currHP, maxHP, level);
  if (isCaught) {
    capture() 
  } else {
    alert('Failed to capture!');
  }
}


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
      })
      .catch(error => console.error('Error fetching or parsing data:', error));
}



function usePotion() {
    if (items[0].quantity > 0) {
      if (Health2 < MAX_HEALTH) {
        Health2 += 20; // Potion restores 20 health
        if (Health2 > MAX_HEALTH) {
         Health2 = MAX_HEALTH;
        }
       items[0].quantity--; // Decrease Potion count
       updateHealthBars();
        audio.Heal.play();
        updateItemUI();
        setTimeout(aiAttack, 1000); // Call AI attack after using Potion
     }
   }
  }
  

  // Function to use Super Potion
function useSuperPotion() {
    if (items[1].quantity > 0) {
      if (Health2 < MAX_HEALTH) {
        Health2 += 50; // Super Potion restores 50 health
       if (Health2 > MAX_HEALTH) {
         Health2 = MAX_HEALTH;
        }
        items[1].quantity--; // Decrease Super Potion count
       updateHealthBars();
        audio.Heal.play();
        updateItemUI();
        setTimeout(aiAttack, 1000); // Call AI attack after using Super Potion
      }
    }
  }


  function updateItemUI() {
      const itemList = document.getElementById('item-list');
      itemList.innerHTML = '';
    
      items.forEach(item => {
          const button = document.createElement('button');
          button.className = 'battle-button';
          button.textContent = `${item.name} - Quantity: ${item.quantity}`;
          button.onclick = () => {
              shakeImages() 
              if (item.name === 'Potion') {
                  usePotion();
              } else if (item.name === 'Super Potion') {
                  useSuperPotion();
              }
              else{
                handleBagItemClick(item)
              }
          };
          itemList.appendChild(button);
      });
  }

// Try to create a function to get the pokemon name from the database and call the funciton below to get the name to show


// Function to update Pokémon names dynamically
function updatePokemonNames() {
    document.getElementById('pokemon-name-1').innerText = pokemonName1;
    document.getElementById('pokemon-name-2').innerText = pokemonName2;
}

// Call the function to update Pokémon names initially
 //call function to get it, 


 const MAX_HEALTH = 100;
let Health1 = 100;
let Health2 = 100;

function resetHealth() {
  Health1 = 100;
  Health2 = 100;
  updateHealthBars(); // Update health bars after resetting values
}

// Function to handle player attack
function playerAttack(attack) {
  Health1 -= attack.power;
  if (Health1 < 0) Health1 = 0;
  updateHealthBars();
  audio.Attack.play()
  if(Health1 <= 0){
    pokemonFaint();
  }
  else{
    setTimeout(aiAttack, 1000); // AI attacks after 1 second
  }
  battleSequence()
}

// Function to handle AI attack (dummy function for demonstration)
function aiAttack() {
  const randomIndex = Math.floor(Math.random() * attacks.length);
  const attack = attacks[randomIndex];
  Health2 -= attack.power;
  if (Health2 < 0) Health2 = 0;
  updateHealthBars();
  audio.Attack.play()
  if(Health2 <= 0){
    pokemonFaint();
  }
}

function pokemonFaint(){
  gsap.to('#overlap', {
    opacity: 1,
    onComplete: () => {
      cancelAnimationFrame(battleAnimationId)
      audio.Battle.stop()
      audio.Map.play()
      animate()
      document.querySelector('#userInterface').style.display = 'none'
      gsap.to('#overlap', {
        opacity: 0
      })
    }
  })
}



// Function to update health bars
function updateHealthBars() {
  const playerHealthBar = document.querySelector('#player-health-bar .health-bar-inner');
  const aiHealthBar = document.querySelector('#pokemon-name-2').nextElementSibling.querySelector('.health-bar-inner');
  playerHealthBar.style.width = `${Health1}%`;
  aiHealthBar.style.width = `${Health2}%`;
}

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
      playerAttack(attack);
  
    };
    attackList.appendChild(button);
  });
}


let userId = 1
let wildPokemonID = Math.floor(Math.random() * 500) + 1; 
let level = Math.floor(Math.random() * 100) + 1; 

        function capture() {
            fetch('http://localhost/PokemonUmbc/src/capture/capture.php', {
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
             wildPokemonID = Math.floor(Math.random() * 500) + 1; 
             level = Math.floor(Math.random() * 100) + 1; 
              fetchUsersTeams()
              alert(message)
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
  // items.forEach(item => {
  //   const button = document.createElement('button');
  //   button.className = 'battle-button';
  //   button.textContent = `${item.name} - Quantity: ${item.quantity}`;
  //   button.onclick = () => {
  //     // Implement the item usage action here
  //     handleBagItemClick(item);
  
  //     console.log(`Used ${item.name}`);
  //   };
  //   itemList.appendChild(button);
  // });
  updateItemUI();
}




// Function to show team list
function showTeamList() {
  document.getElementById('attack-options').classList.remove('active');
  document.getElementById('bag-items').classList.remove('active');
  document.getElementById('team-pokemon').classList.add('active');
  updateItemUI()
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
      audio.Battle.stop()
      audio.Map.play()
      animate()
      document.querySelector('#userInterface').style.display = 'none'
      gsap.to('#overlap', {
        opacity: 0
      })
    }
  })
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

let clicked = false
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
          //deactivate current animation loop
          window.cancelAnimationFrame(animationId)
          audio.Map.stop()
          audio.Battle.play()
          battleZone.initiated = true
          resetHealth()
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
  updateHealthBars();
}

// Maybe animation


 



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

addEventListener('click', () => {
  if(!clicked){
    audio.Map.play()
    clicked = true
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
  "But beware, for the final battle awaits you in the heart of the Enchanted Forest.",
  "There, you will face a secretive creature, shrouded in mystery, whose fury can summon raging storms and monstrous forces.",
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
  if (e.key.toLowerCase() === 's') {
      document.getElementById('dialog-box').style.display = 'block';
      document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
  }
});

document.getElementById('help-icon').addEventListener('click', function () {
  document.getElementById('help-dialog-box').style.display = 'block';
  document.getElementById('help-dialog-text').innerText = helpDialogText[helpDialogIndex];
});

let helpDialogIndex = 0;
const helpDialogText = [
  "To move around, use the arrow keys.",
  "Explore the forest to find Pokémon. (HINT: Go to the extreme right, to the grass.)",
  "Visit the Sacred Grove to find the mythical Pokémon, Lumina.",
  "Train your Pokémon and prepare for battles to protect Eldoria."
];

function nextHelp() {
  helpDialogIndex++;
  if (helpDialogIndex < helpDialogText.length) {
      document.getElementById('help-dialog-text').innerText = helpDialogText[helpDialogIndex];
  } else {
      closeHelpDialog();
  }
}

function closeHelpDialog() {
  document.getElementById('help-dialog-box').style.display = 'none';
  helpDialogIndex = 0;
}

window.addEventListener('load', function() {
  document.getElementById('dialog-box').style.display = 'block';
  document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
});

window.addEventListener('keydown', function(e) {
  if (e.key.toLowerCase() === 'h') {
      document.getElementById('help-dialog-box').style.display = 'block';
      document.getElementById('help-dialog-text').innerText = helpDialogText[helpDialogIndex];
  }
});

gsap.to('#help-icon', {
  opacity: 0.5,
  yoyo: true,
  repeat: -1,
  duration: 0.5
});


fetchPokemonData()
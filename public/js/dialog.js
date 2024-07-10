document.getElementById('npc').addEventListener('click', function () {
	document.getElementById('dialog-box').style.display = 'block';
});

let dialogIndex = 0;
const dialogText = [
	'Welcome, traveler, to the Mystical Forest of Eldoria! This ancient woodland is teeming with hidden wonders and powerful Pokémon waiting to be discovered.',
	"The forest's dense trees and hidden cabins are home to many secrets and mysterious creatures.",
	'Legend has it that deep within the forest lies the Sacred Grove, where the mythical Pokémon, Lumina, is said to reside.',
	'Lumina holds the power to control the elements and bring balance to our world. However, a dark force has emerged from the depths of the ocean, threatening to disrupt this harmony.',
	'To protect Eldoria, you must journey through the forest, challenging the Pokémon guardians that dwell within.',
	'But beware, for the final battle awaits you in the heart of the Enchanted Forest.',
	'There, you will face a secretive creature, shrouded in mystery, whose fury can summon raging storms and monstrous forces.',
	'Gather your courage, train your Pokémon, and uncover the secrets of Eldoria.',
	'Only then can you restore peace to our land and earn the right to meet Lumina.',
	'Your adventure begins now!',
];

export function nextDialog() {
	dialogIndex++;
	if (dialogIndex < dialogText.length) {
		document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
	} else {
		closeDialog();
	}
}

export function closeDialog() {
	document.getElementById('dialog-box').style.display = 'none';
	dialogIndex = 0;
}

gsap.to('#npc', {
	opacity: 0.5,
	yoyo: true,
	repeat: -1,
	duration: 0.5,
});

window.addEventListener('load', function () {
	document.getElementById('dialog-box').style.display = 'block';
	document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
});

window.addEventListener('keydown', function (e) {
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
	'To move around, use the arrow keys.',
	'Explore the forest to find Pokémon. (HINT: Go to the extreme right, to the grass.)',
	'Visit the Sacred Grove to find the mythical Pokémon, Lumina.',
	'Train your Pokémon and prepare for battles to protect Eldoria.',
];

export function nextHelp() {
	helpDialogIndex++;
	if (helpDialogIndex < helpDialogText.length) {
		document.getElementById('help-dialog-text').innerText = helpDialogText[helpDialogIndex];
	} else {
		closeHelpDialog();
	}
}

export function closeHelpDialog() {
	document.getElementById('help-dialog-box').style.display = 'none';
	helpDialogIndex = 0;
}

window.addEventListener('load', function () {
	document.getElementById('dialog-box').style.display = 'block';
	document.getElementById('dialog-text').innerText = dialogText[dialogIndex];
});

window.addEventListener('keydown', function (e) {
	if (e.key.toLowerCase() === 'h') {
		document.getElementById('help-dialog-box').style.display = 'block';
		document.getElementById('help-dialog-text').innerText = helpDialogText[helpDialogIndex];
	}
});

gsap.to('#help-icon', {
	opacity: 0.5,
	yoyo: true,
	repeat: -1,
	duration: 0.5,
});



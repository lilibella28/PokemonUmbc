import { collisions } from './collision.js';
import { grassEncounter } from './grassEncounter.js';
import { Boundary } from './Boundary.js';
import { createMap } from './utils.js';
import { offset } from './config.js';

export const collisionMap = []
export const grassEncounterMap = []

export const boundaries = [];
collisionMap.forEach((row, i) => {
    row.forEach((symbol, j) => {
        if (symbol === 1301) {
            boundaries.push(new Boundary({
                position: {
                    x: j * Boundary.width + offset.x,
                    y: i * Boundary.height + offset.y
                }
            }));
        }
    });
});

export const battleZones = [];
grassEncounterMap.forEach((row, i) => {
    row.forEach((symbol, j) => {
        if (symbol === 1301) {
            battleZones.push(new Boundary({
                position: {
                    x: j * Boundary.width + offset.x,
                    y: i * Boundary.height + offset.y
                }
            }));
        }
    });
});

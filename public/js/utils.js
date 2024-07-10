export function setupCanvas() {
    const canvas = document.querySelector('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 1430;
    canvas.height = 860;
    return { canvas, ctx };
}

export const createMap = (data, sliceSize) => {
    const map = [];
    for (let i = 0; i < data.length; i += sliceSize) {
        map.push(data.slice(i, sliceSize + i));
    }
    return map;
};

export const rectCollide = ({ rect1, rect2 }) => (
    rect1.position.x + rect1.width - 30 >= rect2.position.x &&
    rect1.position.x + 30 <= rect2.position.x + rect2.width &&
    rect1.position.y + 78 <= rect2.position.y + rect2.height &&
    rect1.position.y + rect1.height - 10 >= rect2.position.y
);

export const rectOverlap = ({ rect1, rect2 }) => (
    rect1.position.x + rect1.width - 60 >= rect2.position.x &&
    rect1.position.x + 60 <= rect2.position.x + rect2.width &&
    rect1.position.y + 110 <= rect2.position.y + rect2.height &&
    rect1.position.y + rect1.height - 40 >= rect2.position.y
);

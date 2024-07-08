export function setupCanvas() {
    const canvas = document.querySelector('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 1430;
    canvas.height = 860;
    return { canvas, ctx };
  }
  
  export function startGame() {
    requestAnimationFrame(animate);
  }
  
  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawSprites();
    requestAnimationFrame(animate);
  }
  
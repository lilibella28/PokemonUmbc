class Sprite {
  constructor({ position, image, frames = { max: 1 }, sprites = [], moves = false }) {
    this.position = position;
    this.image = image;
    this.frames = { ...frames, val: 0, delay: 0 };
    this.sprites = sprites;
    this.currentSprite = 0;

    this.width = this.image.width / this.frames.max; // Initialize width based on image and frames
    this.height = this.image.height / this.frames.max; // Initialize height based on image and frames

    this.moves = moves;
    this.flipped = false; // Flag to track if the sprite is flipped
  }

  draw(width = this.width, height = this.height) {
    if (this.flipped) {
      // Flip the image horizontally
      ctx.save();
      ctx.scale(-1, 1); // Flip horizontally
      ctx.drawImage(
        this.image,
        this.frames.val * this.width,
        this.currentSprite * this.height,
        this.image.width / this.frames.max,
        this.image.height / this.frames.max,
        -this.position.x - width, // Adjust x position when flipped
        this.position.y,
        width,
        height
      );
      ctx.restore();
    } else {
      // Draw normally
      ctx.drawImage(
        this.image,
        this.frames.val * this.width,
        this.currentSprite * this.height,
        this.image.width / this.frames.max,
        this.image.height / this.frames.max,
        this.position.x,
        this.position.y,
        width,
        height
      );
    }

    if (!this.moves) {
      this.frames.val = 0;
      return;
    }

    if (this.frames.max > 1) {
      this.frames.delay++;
    }

    if (this.frames.delay % 10 === 0) {
      if (this.frames.val < this.frames.max - 1) {
        this.frames.val++;
      } else {
        this.frames.val = 0;
      }
    }
  }

  flip(shouldFlip) {
    if (shouldFlip !== undefined) {
      this.flipped = shouldFlip; // Set flip state based on parameter
    } else {
      this.flipped = !this.flipped; // Toggle flip state if no parameter is provided
    }
    console.log('Sprite flipped:', this.flipped);
  }
}

class Boundary {
  static width = 64;
  static height = 64;
  constructor({ position }) {
    this.position = position;
    this.width = 64;
    this.height = 64;
  }

  draw() {
    ctx.fillStyle = 'rgba(255, 0, 0, 0.5)';
    ctx.fillRect(this.position.x, this.position.y, this.width, this.height);
  }
}

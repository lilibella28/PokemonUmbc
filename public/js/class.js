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
    this.visible = true;

    // Shake properties
    this.shaking = false;
    this.shakeDuration = 0;
    this.originalPosition = { ...position };
  }

  draw(width = this.width, height = this.height) {
    if (!this.visible) return;

    let xOffset = 0;
    let yOffset = 0;

    if (this.shaking) {
      xOffset = Math.random() * 10 - 5;
      yOffset = Math.random() * 10 - 5;
      this.shakeDuration--;
      if (this.shakeDuration <= 0) {
        this.shaking = false;
        this.position = { ...this.originalPosition };
      }
    }

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
        -(this.position.x + xOffset) - width, // Adjust x position when flipped
        this.position.y + yOffset,
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
        this.position.x + xOffset,
        this.position.y + yOffset,
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
  }

  shake(duration) {
    this.shaking = true;
    this.shakeDuration = duration;
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
    ctx.fillStyle = 'rgba(255, 0, 0, 0)';
    ctx.fillRect(this.position.x, this.position.y, this.width, this.height);
  }
}

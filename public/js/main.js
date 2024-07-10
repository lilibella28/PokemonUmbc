import { setupCanvas } from './utils.js';
import {nextDialog, closeDialog, nextHelp, closeHelpDialog} from './dialog.js'
document.addEventListener('DOMContentLoaded', () => {
    setupCanvas();
    nextDialog();
    closeDialog();
    nextHelp();
    closeHelpDialog()
   
  });
  
  window.addEventListener('keydown', handlePlayerMovement);
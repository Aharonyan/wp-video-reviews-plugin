/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */

 import Hooks from './inc/hooks'

 window.fe_hooks = Hooks;
 
 import './functions.js';
 
 
 import settings_page from './inc/settings-page';
 
 settings_page()

 import video_settings from './inc/video-settings';
 
 video_settings()
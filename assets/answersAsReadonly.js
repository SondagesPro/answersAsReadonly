/* This file is part of answersAsReadonly limesurvey plugin distributed as AGPL */
/* This file is distributed with a CC0 licence */
/* @license magnet:?xt=urn:btih:90dc5c0be029de84e523b9b3922520e79e0e6f08&dn=cc0.txt CC0 */
$(document).on("click",".answersasreadonly-attribute .checkbox-item",function() {
    return false;
});
$(document).on("click",".answersasreadonly-attribute .radio-item",function() {
    return false;
});
$(document).on("keyup keypress keydown",".answersasreadonly-attribute .text-item",function() {
    return false; // Not really needed
});

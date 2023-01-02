/* This file is part of answersAsReadonly limesurvey plugin distributed as AGPL */
/* This file is distributed with a CC0 licence */
/* @license magnet:?xt=urn:btih:90dc5c0be029de84e523b9b3922520e79e0e6f08&dn=cc0.txt CC0 */
$(document).on("click",".answersasreadonly-attribute .checkbox-item",function() {
    return false;
});
$(document).on("click",".answersasreadonly-attribute .radio-item",function() {
    return false;
});
$(document).on("click",".answersasreadonly-attribute .button-item",function() {
    return false;
});
$(document).on("keyup keypress keydown",".answersasreadonly-attribute .text-item",function() {
    //return false; // Disable it : not needed and broke Ctrl + tab
});
$(document).on("ready pjax:complete",function() {
    $(".answersasreadonly-attribute .dropdown-item select option").each(function() {
        $(this).prop('disabled',!$(this).prop('selected'));
    });
    $(".answersasreadonly-attribute .button-item").each(function() {
        $(this).addClass("disabled");
    });
    $(".answersasreadonly-attribute a.upload").each(function() {
        $(this).addClass("disabled");
        $(this).prop("disabled",true);
    });
});
/* READONlY SLIDER , workaround, set as disable hide all content*/
$(document).on('ready pjax:complete',function(){
    $(".answersasreadonly-attribute .slider-item.numeric-item").on('slideStart', function(){
        let previousVal = $(this).find("input.ls-js-hidden").val();
        $(this).data("previousValue",previousVal);
    });
    $(".answersasreadonly-attribute .slider-item.numeric-item").on('slideStop', function(){
        let previousVal = $(this).data("previousValue");
        let sliderid = $(this).attr('id').replace('javatbd','s');
        window.activeSliders[sliderid].setValue(previousVal);
    });
});
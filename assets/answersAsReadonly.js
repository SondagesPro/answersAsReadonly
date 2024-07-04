/* This file is part of answersAsReadonly limesurvey plugin distributed as AGPL */
/* @version 0.5.0 */
/* This file is distributed with a CC0 licence */
/* @license magnet:?xt=urn:btih:90dc5c0be029de84e523b9b3922520e79e0e6f08&dn=cc0.txt CC0 */
'use strict';
$(document).on('click','.answersasreadonly-attribute .checkbox-item',function() {
    return false;
});
$(document).on('click','.answersasreadonly-attribute .radio-item',function() {
    return false;
});
$(document).on('click','.answersasreadonly-attribute .button-item',function() {
    return false;
});
$(document).on('keyup keypress keydown','.answersasreadonly-attribute .text-item',function() {
    //return false; // Disable it : not needed and broke Ctrl + tab
});
$(function() {
    $('.answersasreadonly-attribute .answer-item select option').each(function() {
        $(this).prop('disabled',!$(this).prop('selected'));
    });
    $('.answersasreadonly-attribute .button-item').each(function() {
        $(this).addClass('disabled');
    });
    $('.answersasreadonly-attribute a.upload').each(function() {
        $(this).addClass('disabled');
        $(this).prop('disabled',true);
    });
    $('.answersasreadonly-attribute :radio:not(:checked)').attr('disabled', true);
    $('.answersasreadonly-attribute .checkbox-item').off('click').on("click",function() { return false; });
    $('.answersasreadonly-attribute .radio-item').off('click').on("click",function() { return false; });
    $('.answersasreadonly-attribute .button-item').off('click').on("click",function() { return false; });
});
/* READONlY SLIDER , workaround, set as disable hide all content*/
$(function() {
    $('.answersasreadonly-attribute .slider-item.numeric-item').on('slideStart', function(){
        let previousVal = $(this).find('input.ls-js-hidden').val();
        $(this).data('previousValue',previousVal);
    });
    $('.answersasreadonly-attribute .slider-item.numeric-item').on('slideStop', function(){
        let previousVal = $(this).data('previousValue');
        let sliderid = $(this).attr('id').replace('javatbd','s');
        window.activeSliders[sliderid].setValue(previousVal);
    });
});
/* select2 set as readonly */
if (jQuery.fn.select2) {
    $(function() {
        $('.answersasreadonly-attribute .answer-item select.select2-hidden-accessible').select2('enable', false);
    });
}

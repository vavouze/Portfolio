"use strict";
jQuery(document).ready(function( $ ) {
    var currentlyClickedElement = '';

    $('.color-pick-color').bind("click", function(){
        currentlyClickedElement = this;
    });

    $('.color-pick-color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).css("background","#"+hex);
            $(el).attr("data-value", "#"+hex);
            $(el).parent().children(".color-pick").val("#"+hex);
            $(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor($(this).attr("data-value"));
        },
        onChange: function (hsb, hex, rgb) {
            $(currentlyClickedElement).css("background","#"+hex);
            $(currentlyClickedElement).attr("data-value", "#"+hex);
            $(currentlyClickedElement).parent().children(".color-pick").val("#"+hex);
        }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });


    $('.color-pick').bind('keyup', function(){
        $(this).parent().children(".color-pick-color").css("background", $(this).val());
    });

    window.anpsGetColors = function(type) {
        if (type === 'object') {
            var allColors = {};

            $('.color-pick').each(function() {
                allColors[$(this).attr('name')] = $(this).val();
            });

            console.log(JSON.stringify(allColors));
        } else {
            var allColors = [];

            $('.color-pick').each(function() {
                allColors.push($(this).val());
            });

            console.log(allColors);
        }
    }

    var colors = {
        'default': ["#727272", "#26507a", "#3178bf", "#000", "#000000", "#c1c1c1", "#f9f9f9", "#0f0f0f", "#c4c4c4", "#242424", "#c4c4c4", "#fff", "#fff", "", "#fff", "#000000", "", "", "", "#26507a", "#fff", "#26507a", "#fff", "#3178bf", "#fff", "#26507a", "#fff", "#3178bf", "#fff", "#000000", "#fff", "#ffffff", "#fff", "#26507a", "#26507a", "#ffffff", "#26507a", "#26507a", "#3178bf", "#26507a", "#fff", "#3178bf", "#fff", "#c3c3c3", "#fff", "#737373", "#fff"],
        blue: ["#727272", "#26507a", "#5ebe5f", "#000", "#000000", "#c1c1c1", "#f9f9f9", "#0a2642", "#7e9ab5", "#0a2642", "#c5d8eb", "#13314E", "#fff", "", "#fff", "#000000", "", "", "", "#639fdb", "#26507a", "#26507a", "#fff", "#3178bf", "#fff", "#26507a", "#fff", "#3178bf", "#fff", "#000000", "#fff", "#ffffff", "#fff", "#26507a", "#26507a", "#ffffff", "#26507a", "#26507a", "#3178bf", "#26507a", "#fff", "#3178bf", "#fff", "#c3c3c3", "#fff", "#737373", "#fff"],
        orange: ["#727272", "#ff6600", "#ff9249", "#000", "#000000", "#c1c1c1", "#f9f9f9", "#1a1a1a", "#e6e6e6", "#1a1a1a", "#e6e6e6", "#616161", "#fff", "", "#fff", "#000000", "", "", "", "#ff6600", "#fff", "#ff6600", "#fff", "#ff9249", "#fff", "#ff6600", "#fff", "#ff9249", "#fff", "#000000", "#fff", "#ffffff", "#fff", "#ff6600", "#ff9249", "#ffffff", "#ff6600", "#ff6600", "#ff9249", "#ff6600", "#fff", "#ff9249", "#fff", "#c3c3c3", "#fff", "#737373", "#fff"],
        green: ["#727272", "#2c3e50", "#16a085", "#000", "#000000", "#c1c1c1", "#f9f9f9", "#2c3e50", "#7e9ab5", "#2c3e50", "#c5d8eb", "#3a546e", "#fff", "", "#fff", "#000000", "", "", "", "#16a085", "#fff", "#16a085", "#fff", "#1abc9c", "#fff", "#16a085", "#fff", "#1abc9c", "#fff", "#000000", "#fff", "#ffffff", "#fff", "#16a085", "#1abc9c", "#ffffff", "#16a085", "#16a085", "#1abc9c", "#16a085", "#fff", "#1abc9c", "#fff", "#c3c3c3", "#fff", "#737373", "#fff"],
    };

    $("#predefined_colors label").on("click", function(){
        var table = colors[$('input', this).val()];

        $(".color-pick").each(function(index){
            $(".color-pick").eq(index).val(table[index]);
            $(".color-pick").eq(index).parent().children(".color-pick-color").css("background", table[index]);
            $(".color-pick").eq(index).parent().children(".color-pick-color").attr("data-value", table[index]);
        });
    });

    $(".input-type").on('change', function(){
        if($(this).val() == "dropdown") {
            $(this).parent().parent().children(".validation").hide();
            $(this).parent().parent().children(".label-place-val").children("label").html("Values");
        }
        else {
            $(this).parent().parent().children(".validation").show();
            $(this).parent().parent().children(".label-place-val").children("label").html("Placeholder");
        }
    });
});

/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Peity charts init js
*/

import 'peity/jquery.peity.min.js';

!function ($) {
    "use strict";

    var PeityChart = function () { };

    //create line
    PeityChart.prototype.createLine = function ($element, $strokeColor, $fillColor, $width, $height) {
        $($element).peity("line", {
            fill: $strokeColor,
            stroke: $fillColor,
            width: $width,
            height: $height
        });
        return $($element);
    },

        //init
        PeityChart.prototype.init = function () {
            //live graph
            var updatingChart = this.createLine(".updating-chart", '#5fbeaa', '#5fbeaa', 120, 40);

            setInterval(function () {
                var random = Math.round(Math.random() * 10)
                var values = updatingChart.text().split(",")
                values.shift()
                values.push(random)

                updatingChart
                    .text(values.join(","))
                    .change()
            }, 1000);
        },
        //init
        $.PeityChart = new PeityChart, $.PeityChart.Constructor = PeityChart
}(window.jQuery),

    //initializing 
    function ($) {
        "use strict";
        $.PeityChart.init()
    }(window.jQuery);


//peity charts
$('[data-plugin="peity-pie"]').each(function (idx, obj) {
    var colors = $(this).attr('data-colors') ? $(this).attr('data-colors').split(",") : [];
    var width = $(this).attr('data-width') ? $(this).attr('data-width') : 20; //default is 20
    var height = $(this).attr('data-height') ? $(this).attr('data-height') : 20; //default is 20
    $(this).peity("pie", {
        fill: colors,
        width: width,
        height: height
    });
});
//donut
$('[data-plugin="peity-donut"]').each(function (idx, obj) {
    var colors = $(this).attr('data-colors') ? $(this).attr('data-colors').split(",") : [];
    var width = $(this).attr('data-width') ? $(this).attr('data-width') : 20; //default is 20
    var height = $(this).attr('data-height') ? $(this).attr('data-height') : 20; //default is 20
    $(this).peity("donut", {
        fill: colors,
        width: width,
        height: height
    });
});

$('[data-plugin="peity-donut-alt"]').each(function (idx, obj) {
    $(this).peity("donut");
});

// line
$('[data-plugin="peity-line"]').each(function (idx, obj) {
    $(this).peity("line", $(this).data());
});

// bar
$('[data-plugin="peity-bar"]').each(function (idx, obj) {
    var colors = $(this).attr('data-colors') ? $(this).attr('data-colors').split(",") : [];
    var width = $(this).attr('data-width') ? $(this).attr('data-width') : 20; //default is 20
    var height = $(this).attr('data-height') ? $(this).attr('data-height') : 20; //default is 20
    $(this).peity("bar", {
        fill: colors,
        width: width,
        height: height
    });
});

$('[data-plugin="knob"]').each(function (idx, obj) {
    $(this).knob();
});
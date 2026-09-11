/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Dashboard 4 init
*/

import raphael from 'raphael/raphael.min';
window.Raphael = raphael;
import 'morris.js06/dist/morris';

!function ($) {
    "use strict";

    let Dashboard4 = function () { };

    //creates Bar chart
    Dashboard4.prototype.createBarChart = function (element, data, xkey, ykeys, labels, lineColors) {
        Morris.Bar({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            dataLabels: false,
            hideHover: 'auto',
            resize: true, //defaulted to true
            gridLineColor: 'rgba(65, 80, 95, 0.07)',
            barSizeRatio: 0.2,
            barColors: lineColors
        });
    },

        //creates area chart with dotted
        Dashboard4.prototype.createAreaChartDotted = function (element, pointSize, lineWidth, data, xkey, ykeys, labels, Pfillcolor, Pstockcolor, lineColors) {
            Morris.Area({
                element: element,
                pointSize: 3,
                lineWidth: 1,
                data: data,
                xkey: xkey,
                ykeys: ykeys,
                labels: labels,
                dataLabels: false,
                hideHover: 'auto',
                pointFillColors: Pfillcolor,
                pointStrokeColors: Pstockcolor,
                resize: true,
                smooth: false,
                gridLineColor: 'rgba(65, 80, 95, 0.07)',
                lineColors: lineColors
            });
        },

        //creates Donut chart
        Dashboard4.prototype.createDonutChart = function (element, data, colors) {
            Morris.Donut({
                element: element,
                data: data,
                barSize: 0.2,
                resize: true, //defaulted to true
                colors: colors,
                backgroundColor: 'transparent'
            });
        },
        Dashboard4.prototype.init = function () {

            //creating bar chart
            let $barData = [
                { y: '2012', a: 87 },
                { y: '2013', a: 75 },
                { y: '2014', a: 50 },
                { y: '2015', a: 75 },
                { y: '2016', a: 50 },
                { y: '2017', a: 38 },
                { y: '2018', a: 72 }
            ];
            let barColors = ['#02c0ce'];
            let barDataColors = $("#statistics-chart").data('colors');
            if (barDataColors) {
                barColors = barDataColors.split(",");
            }
            this.createBarChart('statistics-chart', $barData, 'y', ['a'], ["Statistics"], barColors);

            //creating area chart with dotted
            let $areaDotData = [
                { y: '2012', a: 10, b: 20 },
                { y: '2013', a: 75, b: 65 },
                { y: '2014', a: 50, b: 40 },
                { y: '2015', a: 75, b: 65 },
                { y: '2016', a: 50, b: 40 },
                { y: '2017', a: 75, b: 65 },
                { y: '2018', a: 90, b: 60 }
            ];
            let areaColors = ['#4a81d4', "#e3eaef"];
            let areaDataColors = $("#income-amounts").data('colors');
            if (areaDataColors) {
                areaColors = areaDataColors.split(",");
            }
            this.createAreaChartDotted('income-amounts', 0, 0, $areaDotData, 'y', ['a', 'b'], ["Bitcoin", "Litecoin"], ['#ffffff'], ['#999999'], areaColors);

            //creating donut chart
            let $donutData = [
                { label: " Total Sales ", value: 12 },
                { label: " Campaign Send", value: 30 },
                { label: " Daily Sales ", value: 20 }
            ];
            let donutColors = ['#4fc6e1', '#6658dd', '#ebeff2'];
            let donutDataColors = $("#lifetime-sales").data('colors');
            if (donutDataColors) {
                donutColors = donutDataColors.split(",");
            }
            this.createDonutChart('lifetime-sales', $donutData, donutColors);
        },
        //init
        $.Dashboard4 = new Dashboard4, $.Dashboard4.Constructor = Dashboard4
}(window.jQuery),

    //initializing
    function ($) {
        "use strict";
        document.addEventListener('DOMContentLoaded', function () {
            $.Dashboard4.init();
        }, false);
    }(window.jQuery);

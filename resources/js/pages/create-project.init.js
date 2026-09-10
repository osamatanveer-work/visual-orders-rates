/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: File uploads init js
*/

import select2 from 'select2/dist/js/select2.min.js';
select2()
import 'dropzone/dist/min/dropzone.min.js';
import 'flatpickr/dist/flatpickr.min.css';

import 'flatpickr/dist/flatpickr.min.js';

// Dropzone
!function ($) {
    "use strict";

    var FileUpload = function () {
        this.$body = $("body")
    };


    /* Initializing */
    FileUpload.prototype.init = function () {
        // Disable auto discovery

        Dropzone.autoDiscover = false;

        $('[data-plugin="dropzone"]').each(function () {
            var actionUrl = $(this).attr('action')
            var previewContainer = $(this).data('previewsContainer');

            var opts = { url: actionUrl};
            if (previewContainer) {
                opts['previewsContainer'] = previewContainer;
            }

            var uploadPreviewTemplate = $(this).data("uploadPreviewTemplate");
            if (uploadPreviewTemplate) {
                opts['previewTemplate'] = $(uploadPreviewTemplate).html();
            }

            var dropzoneEl = $(this).dropzone(opts);
            
        });
    },

        //init fileupload
        $.FileUpload = new FileUpload, $.FileUpload.Constructor = FileUpload

}(window.jQuery),

//initializing FileUpload
function ($) {
"use strict";
    $.FileUpload.init()
}(window.jQuery);

// Select 2
$('[data-toggle="select2"]').select2();

//Flat picker
$('[data-toggle="flatpicker"]').flatpickr({
    altInput: true,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d",
});
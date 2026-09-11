/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Add New Product
*/

import select2 from 'select2/dist/js/select2.min.js';
select2()
import 'dropzone/dist/min/dropzone.min.js';
import Quill from 'quill/dist/quill.min.js';
import './form-fileuploads.init';

jQuery(document).ready(function () {
    // Snow theme
    var quill = new Quill('#snow-editor', {
        theme: 'snow',
        modules: {
            'toolbar': [[{ 'font': [] }, { 'size': [] }], ['bold', 'italic', 'underline', 'strike'], [{ 'color': [] }, { 'background': [] }], [{ 'script': 'super' }, { 'script': 'sub' }], [{ 'header': [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }], ['direction', { 'align': [] }], ['link', 'image', 'video'], ['clean']]
        },
    });


    // Select2
    $('.select2').select2({ width: '100%' });
});
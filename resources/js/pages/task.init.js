/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Task init js
*/

import dragula from 'dragula/dist/dragula.min.js';
window.dragula = dragula;
import Quill from 'quill/dist/quill.min.js';
import './dragula.init.js';

// Bubble theme
document.addEventListener("DOMContentLoaded", function () {
    var quill = new Quill('#bubble-editor', {
        theme: 'bubble'
    });
})
/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Tickets init js
*/

import DataTable from 'datatables.net/js/jquery.dataTables.min.js';
DataTable()
import 'datatables.net-bs5/js/dataTables.bootstrap5.min.js';
import 'datatables.net-responsive/js/dataTables.responsive.min.js';
import 'datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js';

$(document).ready(function () {
    $('#tickets-table').DataTable({
        "language": {
            "paginate": {
                "previous": "<i class='mdi mdi-chevron-left'>",
                "next": "<i class='mdi mdi-chevron-right'>"
            }
        },
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
    });

    $(".dataTables_length select").addClass('form-select form-select-sm');
    $(".dataTables_length select").removeClass('custom-select custom-select-sm');

    $(".dataTables_length label").addClass('form-label');
});
    <!--Data table Integration-->
    
    <!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
    <script type="text/javascript">
       $(document).ready(function() {
    var buttonCommon = {
        exportOptions: {
            
            // format: {
            //     body: function ( data, row, column, node ) {
            //         //check if type is input using jquery
            //         // return $(data).is("input") ?
            //         // $(data).val():
            //         // data;
            //         // Strip $ from salary column to make it numeric
            //         return column === 17 ?
            //             data.replace( /[$,]/g, '' ) :
            //             data;
            //     }
            // }
            
            format: {
        body: function (data, row, column, node) {
            var retorno = "", tag, respuesta = "", reponer = [];
            tag = $(node).find('input:hidden');
            if (tag.length > 0) { for (i = 0; i < tag.length; i++) { reponer.push(tag[i]); $(tag[i]).remove(); } }
            tag = $(node).find('input:radio');
            if (tag.length > 0) { retorno = retorno + ($(node).find(':checked').length > 0 ? $(node).find(':checked').val() : " "); }
            tag = $(node).find('input:checkbox');
            if (tag.length == 1) {
                retorno = retorno + ($(node).find(':checked').length > 0 ? "Si" : "No");
            } else if (tag.length > 1) { retorno = retorno + ($(node).find(':checked').length > 0 ? $(node).find(':checked').val() : " "); }
            tag = $(node).find('input,select,textarea').not(':radio,:checkbox,:hidden');
            if (tag.length > 0) { retorno = retorno + ($(tag).map(function () { return $(this).val(); }).get().join(',')); }
 
            respuesta = (retorno != "") ? retorno : $.trim($(node).text());
            for (i = 0; i < reponer.length; i++) { $(node).append(reponer[i]); }
 
            return respuesta;
        }
    },
        }
    };
    $('#order-table').dataTable( {
        "lengthMenu": [20, 40, 60, 80, 100, 500, 1000],
        "pageLength": 10,
         dom: 'Bfrtip',
        buttons: ['pageLength','colvis',
            $.extend( true, {}, buttonCommon, {
                extend: 'copyHtml5'
            } ),
            $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5'
            } ),
            $.extend( true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'TABLOID',
                footer: true,
            } ) 
        ]
    } );
   
} );
   </script>
    
    
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/jquery.meanmenu.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/scrollbar/mCustomScrollbar-active.js"></script>
    <!-- metisMenu JS
		============================================ -->
    <script src="js/metisMenu/metisMenu.min.js"></script>
    <script src="js/metisMenu/metisMenu-active.js"></script>
    <!-- morrisjs JS
		============================================ -->
    <script src="js/morrisjs/raphael-min.js"></script>
    <script src="js/morrisjs/morris.js"></script>
    <script src="js/morrisjs/morris-active.js"></script>
    <!-- morrisjs JS
		============================================ -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>
    <script src="js/sparkline/jquery.charts-sparkline.js"></script>
    <!-- calendar JS
		============================================ -->
    <script src="js/calendar/moment.min.js"></script>
    <script src="js/calendar/fullcalendar.min.js"></script>
    <script src="js/calendar/fullcalendar-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
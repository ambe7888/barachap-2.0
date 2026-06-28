<script src="<?php echo e(asset('assets/backend/js/jquery.dataTables.min.js')); ?>"></script>
<script>
     (function($){
        "use strict";
        $(document).ready(function() {
            $('.dataTablesExample').DataTable();
            $('.table-wrap > table').DataTable( {
                "order": [[ 1, "desc" ]],
                'columnDefs' : [{
                    'targets' : 'no-sort',
                    "orderable" : false
                }]
            } );
        });
    })(jQuery)
</script>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/components/datatable/js.blade.php ENDPATH**/ ?>
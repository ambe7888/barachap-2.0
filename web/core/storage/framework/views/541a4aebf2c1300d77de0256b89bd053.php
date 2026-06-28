<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            // Initialize Select2 for add modal
            $('#type').select2({
                dropdownParent: $('#addModal')
            });

            $('#edit_type').select2({
                dropdownParent: $('#editReasonModal')
            });

            $(document).on('click','.add_title',function(){
                let type = $('#type').val();
                let title = $('#title').val();
                if(title == '' || type == ''){
                    toastr_warning_js("<?php echo e(__('Both field is required !')); ?>");
                    return false;
                }
            });

            $(document).on('click','.edit_reason',function(){
                let type = $('#type').val();
                let title = $('#edit_title').val();
                if(title == '' || type == ''){
                    toastr_warning_js("<?php echo e(__('Both field is required !')); ?>");
                    return false;
                }
            });

            // show edit modal
            $(document).on('click','.edit_reason_modal',function(){
                let id = $(this).data('id');
                let edit_type = $(this).data('type');
                let title = $(this).data('title');
                $('#reason_id').val(id);
                $('#edit_title').val(title);
                $('#edit_type').val(edit_type).trigger('change');
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let string_search = $('#string_search').val();
                types(page,string_search);
            });
            function types(page,string_search){
                $.ajax({
                     url:"<?php echo e(route('admin.report.reason.paginate.data').'?page='); ?>" + page,
                    data:{string_search:string_search},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search category
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"<?php echo e(route('admin.report.reason.search')); ?>",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"<?php echo e(__('Nothing Found')); ?>"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            });
        });
    }(jQuery));
</script>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/reasons/reason-js.blade.php ENDPATH**/ ?>
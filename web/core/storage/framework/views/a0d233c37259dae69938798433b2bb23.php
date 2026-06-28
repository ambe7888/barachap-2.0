<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            // change status
            $(document).on('click','.swal_status_change',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '<?php echo e(__("Are you sure to change status complete? Once you done you can not revert this !!")); ?>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "<?php echo e(__('Yes, change it!')); ?>"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });


            // live search
            $(document).on('keyup','.search_result',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"<?php echo e(route('admin.job.search')); ?>",
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

            // pagination
            $(document).on('click', '.pagination li a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                notices(page);
            });
            function notices(page){
                $.ajax({
                    url:"<?php echo e(route('admin.job.paginate').'?page='); ?>" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

        });
    }(jQuery));
</script>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/JobPost\resources/views/backend/jobs/js.blade.php ENDPATH**/ ?>
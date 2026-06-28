<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('All Pages')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title"><?php echo e(__('All Pages')); ?></h4>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-bulk-delete')): ?>
                            <?php if (isset($component)) { $__componentOriginal41fc2efab414de3fc9c6739ba1ffcc6e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal41fc2efab414de3fc9c6739ba1ffcc6e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.bulk-action.bulk-action','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('bulk-action.bulk-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal41fc2efab414de3fc9c6739ba1ffcc6e)): ?>
<?php $attributes = $__attributesOriginal41fc2efab414de3fc9c6739ba1ffcc6e; ?>
<?php unset($__attributesOriginal41fc2efab414de3fc9c6739ba1ffcc6e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal41fc2efab414de3fc9c6739ba1ffcc6e)): ?>
<?php $component = $__componentOriginal41fc2efab414de3fc9c6739ba1ffcc6e; ?>
<?php unset($__componentOriginal41fc2efab414de3fc9c6739ba1ffcc6e); ?>
<?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <div class="btn-wrapper">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-add')): ?>
                                <a href="<?php echo e(route('admin.page.new')); ?>" class="cmnBtn btn_5 btn_bg_blue radius-5"><?php echo e(__('Add New Page')); ?></a>
                               <?php endif; ?>
                            </div>
                            <div class="d-flex text-right w-100 mt-3">
                                <input class="form__control page_string_search" name="string_search" placeholder="<?php echo e(__('Search')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($component)) { $__componentOriginal4bb59b834d778ff0cb72af5a473e2885 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4bb59b834d778ff0cb72af5a473e2885 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.validation.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('validation.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4bb59b834d778ff0cb72af5a473e2885)): ?>
<?php $attributes = $__attributesOriginal4bb59b834d778ff0cb72af5a473e2885; ?>
<?php unset($__attributesOriginal4bb59b834d778ff0cb72af5a473e2885); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4bb59b834d778ff0cb72af5a473e2885)): ?>
<?php $component = $__componentOriginal4bb59b834d778ff0cb72af5a473e2885; ?>
<?php unset($__componentOriginal4bb59b834d778ff0cb72af5a473e2885); ?>
<?php endif; ?>
                <div class="tableStyle_three mt-4">
                    <div class="table_wrapper custom_Table">
                        <div class="search_page_result">
                            <?php echo $__env->make('backend.pages.page.search-page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-bulk-delete')): ?>
    <?php if (isset($component)) { $__componentOriginal996fed7ae655ce20bc4d8081dd84ac5f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal996fed7ae655ce20bc4d8081dd84ac5f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.bulk-action.bulk-action-js','data' => ['url' => route('admin.page.bulk.action')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('bulk-action.bulk-action-js'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.page.bulk.action'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal996fed7ae655ce20bc4d8081dd84ac5f)): ?>
<?php $attributes = $__attributesOriginal996fed7ae655ce20bc4d8081dd84ac5f; ?>
<?php unset($__attributesOriginal996fed7ae655ce20bc4d8081dd84ac5f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal996fed7ae655ce20bc4d8081dd84ac5f)): ?>
<?php $component = $__componentOriginal996fed7ae655ce20bc4d8081dd84ac5f; ?>
<?php unset($__componentOriginal996fed7ae655ce20bc4d8081dd84ac5f); ?>
<?php endif; ?>
    <?php endif; ?>
    <script type="text/javascript">
        (function(){
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
                $(document).on('keyup','.page_string_search',function(){
                    let string_search = $(this).val();
                    $.ajax({
                        url:"<?php echo e(route('admin.page.search')); ?>",
                        method:'GET',
                        data:{string_search:string_search},
                        success:function(res){
                            if(res.status=='nothing'){
                                $('.search_page_result').html('<h5 class="text-center text-danger">'+"<?php echo e(__('Nothing Found')); ?>"+'</h5>');
                            }else{
                                $('.search_page_result').html(res);
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
                        url:"<?php echo e(route('admin.page.paginate').'?page='); ?>" + page,
                        success:function(res){
                            $('.search_page_result').html(res);
                        }
                    });
                }
            });
        })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/page/index.blade.php ENDPATH**/ ?>
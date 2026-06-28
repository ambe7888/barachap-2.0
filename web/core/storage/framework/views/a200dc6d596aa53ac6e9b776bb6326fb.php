<?php $__env->startSection('title', __('Import Areas')); ?>
<?php $__env->startSection('content'); ?>
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-8">
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
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title"><?php echo e(__('Import Areas (only csv file)')); ?></h4>
                        <div class="customMarkup__single__inner mt-4">
                            <?php if(empty($import_data)): ?>
                                <form action="<?php echo e(route('admin.area.import.csv.update.settings')); ?>" method="post" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="#" class="label-title"><?php echo e(__('File')); ?></label>
                                        <input type="file" name="csv_file" accept=".csv" class="form-control" required>
                                        <div class="text-info"><?php echo e(__('only csv file are allowed with separate by (,) comma.')); ?></div>
                                    </div>
                                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 loading-btn"><?php echo e(__('Submit')); ?></button>
                                </form>
                            <?php else: ?>
                                <?php
                                    $option_markup = '';
                                        foreach(current($import_data) as $map_item ){
                                            $option_markup .= '<option value="'.trim($map_item).'">'.$map_item.'</option>';
                                        }
                                ?>

                                <form action="<?php echo e(route('admin.area.import.database')); ?>" method="post" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <?php echo csrf_field(); ?>
                                    <table class="table table-striped">
                                        <thead>
                                        <th style="width: 200px"><?php echo e(__('Field Name')); ?></th>
                                        <th><?php echo e(__('Set Field')); ?></th>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><h6><?php echo e(__('State')); ?></h6></td>
                                            <?php $all_states = \Modules\CountryManage\app\Models\State::all_states(); ?>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form__control radius-5 select2_activation" name="state_id" id="state_id">
                                                        <option value=""><?php echo e(__('Select State')); ?></option>
                                                        <?php $__currentLoopData = $all_states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $states): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($states->id); ?>"><?php echo e($states->state); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <p class="text-info"><?php echo e(__('Select State')); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h6><?php echo e(__('City')); ?></h6></td>
                                            <?php $cities = \Modules\CountryManage\app\Models\City::all_cities(); ?>
                                            <td>
                                                <div class="form-group">
                                                    <select name="city_id" id="city_id" class="get_state_city form__control radius-5 select2_activation">
                                                        <option value=""><?php echo e(__('Select City')); ?></option>
                                                    </select>
                                                </div>
                                                <p class="text-info"><?php echo e(__('Select City')); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h6><?php echo e(__('Area')); ?></h6></td>
                                            <td>
                                                <div class="form__input__single">
                                                    <select name="area" class="form__control radius-5 select2_activation">
                                                        <option value=""><?php echo e(__('Select Field')); ?></option>
                                                        <?php echo $option_markup; ?>

                                                    </select>
                                                </div>
                                                <p class="text-info"><?php echo e(__('Select area and only unique areas added automatically according to the selected state.')); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><h6><?php echo e(__('Status')); ?></h6></td>
                                            <td>
                                                <div class="form__input__single">
                                                    <select class="form-control mapping_select">
                                                        <option value="1"><?php echo e(__('Publish')); ?></option>
                                                        <option value="0"><?php echo e(__('Draft')); ?></option>
                                                    </select>
                                                    <input type="hidden" name="status" value="1">
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 loading-btn"><?php echo e(__('Import')); ?></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){

                $(document).on('click','.loading-btn',function (){
                    $(this).append('<i class="ml-2 fas fa-spinner fa-spin"></i>')
                });

                $(document).on('change','.mapping_select',function (){
                    $('.mapping_select option').attr('disabled',false);
                    $(this).next('input').val($(this).val());
                    let allValue = $('.mapping_select');
                    $.each(allValue,function (index,item){
                        $('.mapping_select option[value="'+$(this).val()+'"]').attr('disabled',true);
                    });
                })

                // change country and get state
                $(document).on('change','#state_id',function (){
                    let state = $(this).val();
                    $.ajax({
                        method: 'post',
                        url: "<?php echo e(route('au.state.all')); ?>",
                        data: {
                            state: state
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                let all_options = "<option value=''><?php echo e(__('Select State')); ?></option>";
                                let all_cities = res.states;
                                $.each(all_cities, function(index, value) {
                                    all_options += "<option value='" + value.id +
                                        "'>" + value.city + "</option>";
                                });
                                $(".get_state_city").html(all_options);
                                if(all_cities.length <= 0){
                                    $(".info_msg").html('<span class="text-danger"> <?php echo e(__('No state found for selected country!')); ?> <span>');
                                }
                            }
                        }
                    });
                });
            });
        }(jQuery));
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/CountryManage\resources/views/area/import-area.blade.php ENDPATH**/ ?>
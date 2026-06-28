<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Language Settings')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <?php if (isset($component)) { $__componentOriginale3fe6bb2f0f61d925063cbbce78cba4d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3fe6bb2f0f61d925063cbbce78cba4d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.datatable.css','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('datatable.css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3fe6bb2f0f61d925063cbbce78cba4d)): ?>
<?php $attributes = $__attributesOriginale3fe6bb2f0f61d925063cbbce78cba4d; ?>
<?php unset($__attributesOriginale3fe6bb2f0f61d925063cbbce78cba4d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3fe6bb2f0f61d925063cbbce78cba4d)): ?>
<?php $component = $__componentOriginale3fe6bb2f0f61d925063cbbce78cba4d; ?>
<?php unset($__componentOriginale3fe6bb2f0f61d925063cbbce78cba4d); ?>
<?php endif; ?>
    <style>
        .select2-container {
             z-index: 0;
        }

        /* styles.css */

        .dropdown-menu {
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu li {
            list-style: none;
            margin: 10px 10px 10px 10px; /* Space between list items */
            justify-content: center!important;
            align-items: center;
            text-align: center;
        }

        .dropdown-menu li a{
         justify-content: center!important;
        }

        .dropdown-menu li a i{
         justify-content: center!important;
        }

        .dropdown-menu li:last-child {
            margin-bottom: 5px; /* Remove margin from the last item */
        }

        .dropdown-menu .dropdown-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            background-color: #f8f9fa;
            transition: background-color 0.2s;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #e2e6ea;
        }

        .dropdown-menu .dropdown-item i {
            margin-left: 10px;
        }

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row g-4 mt-0">
        <div class="col-xl-9 col-lg-9">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <h2 class="dashboard__card__header__title mb-3"><?php echo e(__('Language Settings')); ?></h2>
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
                    <div class="table_wrapper custom_dataTable">
                        <table class="dataTablesExample">
                            <thead>
                                <th><?php echo e(__('ID')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Direction')); ?></th>
                                <th><?php echo e(__('Slug')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Default')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $all_lang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($data->id); ?></td>
                                        <td><?php echo e($data->name); ?></td>
                                        <td><?php echo e(strtoupper($data->direction)); ?></td>
                                        <td><?php echo e($data->slug); ?></td>
                                        <td><?php echo e($data->status); ?></td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('languages-words-edit')): ?>
                                                <?php if($data->default == 1): ?>
                                                    <a href="javascript:void(0)" class="alert alert-success"><?php echo e(__("Default")); ?></a>
                                                <?php elseif($data->status === 'publish'): ?>
                                                    <?php if (isset($component)) { $__componentOriginal8898331313048d29e698fbc918ef7034 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8898331313048d29e698fbc918ef7034 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.change-default-lang','data' => ['url' => route('admin.languages.default',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.change-default-lang'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.languages.default',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8898331313048d29e698fbc918ef7034)): ?>
<?php $attributes = $__attributesOriginal8898331313048d29e698fbc918ef7034; ?>
<?php unset($__attributesOriginal8898331313048d29e698fbc918ef7034); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8898331313048d29e698fbc918ef7034)): ?>
<?php $component = $__componentOriginal8898331313048d29e698fbc918ef7034; ?>
<?php unset($__componentOriginal8898331313048d29e698fbc918ef7034); ?>
<?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- DropDown -->
                                            <div class="dropdown custom__dropdown">
                                                <button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="las la-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end " aria-labelledby="dropdownMenuButton1">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('languages-words-edit')): ?>
                                                        <?php if($data->default != 1): ?>
                                                            <li>
                                                               <?php if (isset($component)) { $__componentOriginal7973b0ce98592c79f9209abd6e46a09b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7973b0ce98592c79f9209abd6e46a09b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup.delete-popup','data' => ['url' => route('admin.languages.delete',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('popup.delete-popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.languages.delete',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7973b0ce98592c79f9209abd6e46a09b)): ?>
<?php $attributes = $__attributesOriginal7973b0ce98592c79f9209abd6e46a09b; ?>
<?php unset($__attributesOriginal7973b0ce98592c79f9209abd6e46a09b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7973b0ce98592c79f9209abd6e46a09b)): ?>
<?php $component = $__componentOriginal7973b0ce98592c79f9209abd6e46a09b; ?>
<?php unset($__componentOriginal7973b0ce98592c79f9209abd6e46a09b); ?>
<?php endif; ?>
                                                            <li>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                   <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.languages.words.all',$data->slug)); ?>"
                                                           title="<?php echo e(__('Edit Frontend Words')); ?>" class="cmnBtn btn_5 btn_bg_info radius-5">
                                                            <?php echo e(__('Edit All Words')); ?>

                                                        </a>
                                                    <li>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('languages-words-edit')): ?>
                                                      <li>
                                                          <a href="#"
                                                             data-bs-toggle="modal"
                                                             data-bs-target="#language_item_edit_modal"
                                                             class="cmnBtn btn_5 btn_bg_warning radius-5 btnIcon radius-5 lang_edit_btn"
                                                             data-id="<?php echo e($data->id); ?>"
                                                             data-name="<?php echo e($data->name); ?>"
                                                             data-slug="<?php echo e($data->slug); ?>"
                                                             data-status="<?php echo e($data->status); ?>"
                                                             data-direction="<?php echo e($data->direction); ?>">
                                                              <i class="las la-pencil-alt"></i>
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a  href="#"
                                                              data-bs-toggle="modal"
                                                              data-bs-target="#language_item_clone_modal"
                                                              class="cmnBtn btn_6 btn_bg_secondary btnIcon radius-5 lang_clone_btn"
                                                              data-id="<?php echo e($data->id); ?>">
                                                              <i class="las la-copy"></i>
                                                          </a>
                                                      </li>
                                                    <?php endif; ?>

                                                </ul>
                                            </div>


                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('languages-add')): ?>
        <div class="col-xl-3 col-lg-3">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <h2 class="dashboard__card__header__title mb-3"><?php echo e(__('Add New Language')); ?></h2>
                <form action="<?php echo e(route('admin.languages.new')); ?>" method="POST" class="new_language_form">
                    <?php echo csrf_field(); ?>
                    <div class="form__input__flex">
                        <div class="form__input__single">
                            <label for="name" class="form__input__single__label"><?php echo e(__('Languages')); ?></label>
                            <input type="hidden" name="name">
                            <select name="language_select" class="form__control radius-5">
                                <?php if (isset($component)) { $__componentOriginale98fbdf64cd989461203621a1f378ebb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale98fbdf64cd989461203621a1f378ebb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.languages.languages-list','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('languages.languages-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale98fbdf64cd989461203621a1f378ebb)): ?>
<?php $attributes = $__attributesOriginale98fbdf64cd989461203621a1f378ebb; ?>
<?php unset($__attributesOriginale98fbdf64cd989461203621a1f378ebb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale98fbdf64cd989461203621a1f378ebb)): ?>
<?php $component = $__componentOriginale98fbdf64cd989461203621a1f378ebb; ?>
<?php unset($__componentOriginale98fbdf64cd989461203621a1f378ebb); ?>
<?php endif; ?>
                            </select>
                        </div>
                        <div class="form__input__single">
                            <label for="direction" class="form__input__single__label"><?php echo e(__('Direction')); ?></label>
                            <select name="direction" id="direction" class="form__control radius-5">
                                <option value="ltr"><?php echo e(__('LTR')); ?></option>
                                <option value="rtl"><?php echo e(__("RTL")); ?></option>
                            </select>
                        </div>
                        <div class="form__input__single">
                            <label for="status" class="form__input__single__label"><?php echo e(__('Status')); ?></label>
                            <select name="status" id="status" class="form__control radius-5">
                                <option value="publish"><?php echo e(__('Publish')); ?></option>
                                <option value="draft"><?php echo e(__("Draft")); ?></option>
                            </select>
                        </div>
                        <div class="form__input__single">
                            <label for="slug" class="form__input__single__label"><?php echo e(__('Slug')); ?></label>
                            <input class="form__control" name="slug" readonly>
                        </div>
                    </div>
                    <div class="btn_wrapper mt-4">
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5"><?php echo e(__('Add New')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

    <!-- Languages Edit Modal -->
    <div class="modal fade" id="language_item_edit_modal">
        <div class="modal-dialog">
            <div class="popup_contents modal-content">
                <div class="popup_contents__header">
                    <div class="popup_contents__header__flex">
                        <div class="popup_contents__header__contents">
                            <h2 class="popup_contents__header__title"><?php echo e(__('Edit Language')); ?></h2>
                        </div>
                        <div class="popup_contents__header__close" data-bs-dismiss="modal">
                            <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                        </div>
                    </div>
                </div>
                <form action="<?php echo e(route("admin.languages.update")); ?>" method="post" class="edit_language_form">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="lang_id" id="lang_id">

                    <div class="popup_contents__body">
                        <div class="form__input__single">
                            <label for="email" class="form__input__single__label"><?php echo e(__('Languages')); ?></label>
                            <input type="hidden" name="name">
                            <select name="language_select" class="form__control radius-5">
                                <?php if (isset($component)) { $__componentOriginale98fbdf64cd989461203621a1f378ebb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale98fbdf64cd989461203621a1f378ebb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.languages.languages-list','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('languages.languages-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale98fbdf64cd989461203621a1f378ebb)): ?>
<?php $attributes = $__attributesOriginale98fbdf64cd989461203621a1f378ebb; ?>
<?php unset($__attributesOriginale98fbdf64cd989461203621a1f378ebb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale98fbdf64cd989461203621a1f378ebb)): ?>
<?php $component = $__componentOriginale98fbdf64cd989461203621a1f378ebb; ?>
<?php unset($__componentOriginale98fbdf64cd989461203621a1f378ebb); ?>
<?php endif; ?>
                            </select>
                        </div>
                        <div class="form__input__single">
                            <label for="direction" class="form__input__single__label"><?php echo e(__('Direction')); ?></label>
                            <select name="direction" id="edit_direction" class="form-control">
                                <option value="ltr"><?php echo e(__('LTR')); ?></option>
                                <option value="rtl"><?php echo e(__("RTL")); ?></option>
                            </select>
                        </div>
                        <div class="form__input__single">
                            <label for="edit_status" class="form__input__single__label"><?php echo e(__('Status')); ?></label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="publish"><?php echo e(__('Publish')); ?></option>
                                <option value="draft"><?php echo e(__("Draft")); ?></option>
                            </select>
                        </div>
                        <div class="form__input__single">
                            <label for="edit_slug" class="form__input__single__label"><?php echo e(__('Slug')); ?></label>
                            <input type="text" class="form__control radius-5" name="slug" id="edit_slug" readonly>
                        </div>
                    </div>
                    <div class="popup_contents__footer flex_btn justify-content-end profile-border-top">
                        <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></a>
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5"><?php echo e(__('Save Changes')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Languages clone Modal -->
    <div class="modal fade" id="language_item_clone_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="popup_contents modal-content">
            <div class="popup_contents__header">
                <div class="popup_contents__header__flex">
                    <div class="popup_contents__header__contents">
                        <h2 class="popup_contents__header__title"><?php echo e(__('Clone To New Languages')); ?></h2>
                    </div>
                    <div class="popup_contents__header__close" data-bs-dismiss="modal">
                        <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                    </div>
                </div>
            </div>
            <div class="popup_contents__body">
                <span class="alert alert-info"><?php echo e(__('it will copy all content of all static sections, header slider, key features, contact info, support info, pages, menus')); ?></span>

                <form action="<?php echo e(route('admin.languages.clone')); ?>" method="post" class="edit_language_form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="">
                    <div class="form__input__single">
                        <label for="email" class="form__input__single__label"><?php echo e(__('Languages')); ?></label>
                        <input type="hidden" name="name">
                        <select name="language_select" class="form__control radius-5">
                            <?php if (isset($component)) { $__componentOriginale98fbdf64cd989461203621a1f378ebb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale98fbdf64cd989461203621a1f378ebb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.languages.languages-list','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('languages.languages-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale98fbdf64cd989461203621a1f378ebb)): ?>
<?php $attributes = $__attributesOriginale98fbdf64cd989461203621a1f378ebb; ?>
<?php unset($__attributesOriginale98fbdf64cd989461203621a1f378ebb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale98fbdf64cd989461203621a1f378ebb)): ?>
<?php $component = $__componentOriginale98fbdf64cd989461203621a1f378ebb; ?>
<?php unset($__componentOriginale98fbdf64cd989461203621a1f378ebb); ?>
<?php endif; ?>
                        </select>
                    </div>

                    <div class="form__input__single">
                        <label for="direction" class="form__input__single__label"><?php echo e(__('Direction')); ?></label>
                        <select name="direction" id="direction" class="form__control radius-5">
                            <option value="ltr"><?php echo e(__('LTR')); ?></option>
                            <option value="rtl"><?php echo e(__("RTL")); ?></option>
                        </select>
                    </div>
                    <div class="form__input__single">
                        <label for="status" class="form__input__single__label"><?php echo e(__('Direction')); ?></label>
                        <select name="status" class="form__control radius-5">
                            <option value="publish"><?php echo e(__('Publish')); ?></option>
                            <option value="draft"><?php echo e(__("Draft")); ?></option>
                        </select>
                    </div>
                    <div class="form__input__single">
                        <label for="slug" class="form__input__single__label"><?php echo e(__('Slug')); ?></label>
                        <input type="text" class="form__control radius-5" name="slug" readonly>
                    </div>
                    <div class="popup_contents__footer flex_btn justify-content-end profile-border-top">
                        <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></a>
                        <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5"><?php echo e(__('Submit')); ?></button>
                    </div>
                 </form>
            </div>
        </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php if (isset($component)) { $__componentOriginal579359c93efc143f4744524389ba1039 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal579359c93efc143f4744524389ba1039 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.datatable.js','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('datatable.js'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal579359c93efc143f4744524389ba1039)): ?>
<?php $attributes = $__attributesOriginal579359c93efc143f4744524389ba1039; ?>
<?php unset($__attributesOriginal579359c93efc143f4744524389ba1039); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal579359c93efc143f4744524389ba1039)): ?>
<?php $component = $__componentOriginal579359c93efc143f4744524389ba1039; ?>
<?php unset($__componentOriginal579359c93efc143f4744524389ba1039); ?>
<?php endif; ?>
    <script>
        (function($){
            "use strict";

            $(document).ready(function () {

                $(document).on('change', 'select[name="language_select"]', function () {
                    let el = $(this);
                    let name = el.parent().find('select[name="language_select"] option[value="'+el.val()+'"]' ).text()
                    el.parent().find('input[name="name"]').val(name)
                    el.parent().parent().find('input[name="slug"]').val(el.val())
                });

                $(document).on('click', '.lang_edit_btn', function () {
                    let el = $(this);
                    let id = el.data('id');
                    let name = el.data('name');
                    let slug = el.data('slug');
                    let form = $('#language_item_edit_modal');


                    form.find('#lang_id').val(id);
                    form.find('input[name="name"]').val(name);
                    form.find('select[name="language_select"] option[value="'+slug+'"]').attr('selected',true);
                    form.find('#edit_slug').val(slug);
                    form.find('#edit_direction option[value="' + el.data('direction') + '"]').prop('selected', true);
                    form.find('#edit_status option[value="' + el.data('status') + '"]').prop('selected', true);
                });

                $(document).on('click', '.lang_clone_btn', function () {
                    let el = $(this);
                    let id = el.data('id');
                    let form = $('#language_item_clone_modal');
                    form.find('input[name="id"]').val(id);
                });
            });
        })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/languages/index.blade.php ENDPATH**/ ?>
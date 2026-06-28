<div class="modal fade" id="adminPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo e(__('Change Password')); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.password.change')); ?>" method="post" id="userPasswordModalForm">
                <input type="hidden" name="admin_id_for_change_password" id="admin_id_for_change_password" value="">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <?php if (isset($component)) { $__componentOriginal7402aa1f0ef356e6819f9aab3e9a0e98 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7402aa1f0ef356e6819f9aab3e9a0e98 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.password','data' => ['title' => __('Enter new password'),'name' => 'password','id' => 'password','class' => 'form-control','placeholder' => __('Enter New password')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.password'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Enter new password')),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('password'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('password'),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('form-control'),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Enter New password'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7402aa1f0ef356e6819f9aab3e9a0e98)): ?>
<?php $attributes = $__attributesOriginal7402aa1f0ef356e6819f9aab3e9a0e98; ?>
<?php unset($__attributesOriginal7402aa1f0ef356e6819f9aab3e9a0e98); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7402aa1f0ef356e6819f9aab3e9a0e98)): ?>
<?php $component = $__componentOriginal7402aa1f0ef356e6819f9aab3e9a0e98; ?>
<?php unset($__componentOriginal7402aa1f0ef356e6819f9aab3e9a0e98); ?>
<?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    <button href="<?php echo e(route('admin.create')); ?>" class="cmnBtn btn_5 btn_bg_blue radius-5 update_admin_password"><?php echo e(__('Change Password')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/RolePermission\resources/views/admin-manage/password-modal.blade.php ENDPATH**/ ?>
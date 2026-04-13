<nav id="sidebar">
    <div class="sidebar-logo">
        <img src="<?php echo e(asset('img/planet_logo.png')); ?>" alt="Logo" />
        <span>Projecta</span>
    </div>
    <ul>
        <li><a href="<?php echo e(route('dashboard')); ?>" class="<?php echo $__env->yieldContent('nav_dashboard_active'); ?>">Dashboard</a></li>
        <li><a href="<?php echo e(route('admin.projects')); ?>" class="<?php echo $__env->yieldContent('nav_projects_active'); ?>">Projects</a></li>
        <li><a href="<?php echo e(route('admin.tickets')); ?>" class="<?php echo $__env->yieldContent('nav_tickets_active'); ?>">Tickets</a></li>
        <li><a href="<?php echo e(route('admin.clients')); ?>" class="<?php echo $__env->yieldContent('nav_clients_active'); ?>">Clients</a></li>
        <li><a href="<?php echo e(route('collaborators')); ?>" class="<?php echo $__env->yieldContent('nav_collaborators_active'); ?>">Collaborators</a></li>
        <li><a href="<?php echo e(route('admin.profile')); ?>" class="<?php echo $__env->yieldContent('nav_profile_active'); ?>">Profile</a></li>
        <li><a href="<?php echo e(route('admin.settings')); ?>" class="<?php echo $__env->yieldContent('nav_settings_active'); ?>">Settings</a></li>
        <li>
            <a href="<?php echo e(route('logout')); ?>" class="<?php echo $__env->yieldContent('nav_logout_active'); ?>"
                onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                Logout
            </a>
            <form id="logout-form-admin" method="POST" action="<?php echo e(route('logout')); ?>" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </li>
    </ul>
    <div class="sidebar-footer">
        <span>Connected as ADMIN</span>
    </div>
</nav>
<?php /**PATH C:\Users\coren\Documents\Alternance\Projets\Prod\mini-prod-laravel\app\laravel\resources\views/layouts/partials/navbar-admin.blade.php ENDPATH**/ ?>
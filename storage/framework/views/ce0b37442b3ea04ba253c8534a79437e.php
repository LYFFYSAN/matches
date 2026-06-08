<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', '⚽ World Cup 2026 Highlights'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="<?php echo e(route('home')); ?>" class="navbar-brand">
            ⚽ <span>World Cup 2026</span>
        </a>
        <div class="navbar-links">
            <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Matches</a>
            <a href="<?php echo e(route('admin.login')); ?>" class="btn-admin">Admin</a>
        </div>
    </div>
</nav>

<main class="main-content">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="footer">
    <p>Built for fans who sleep through 2am kickoffs. No spoilers, just football. ⚽</p>
</footer>

<script src="<?php echo e(asset('js/app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\ufcmsila\Desktop\matches\worldcup-scorebat\resources\views/layouts/app.blade.php ENDPATH**/ ?>
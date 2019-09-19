<?php if(session('notice')): ?>
    <script type="text/javascript">

        toastr.options = {
            closeButton: true,                  //是否显示关闭按钮
            debug: false,                       //是否使用debug模式
            progressBar: true,                  //是否显示进度条
            positionClass: "toast-top-center",   //弹出窗的位置
            showDuration: "300",                //显示动作时间
            preventDuplicates: true,            //提示框只出现一次
            hideDuration: "300",                //隐藏动作时间
            timeOut: "3000",                    //自动关闭超时时间
            extendedTimeOut: "1000",            ////加长展示时间
            showEasing: "swing",                //显示时的动画缓冲方式
            hideEasing: "linear",               //消失时的动画缓冲方式
            showMethod: "fadeIn",               //显示时的动画方式
            hideMethod: "fadeOut"               //消失时的动画方式
        };
        toastr.success('<?php echo e(session('notice')); ?>');
    </script>
<?php endif; ?>

<?php if(session('alert')): ?>
    <script type="text/javascript">

        toastr.options = {
            closeButton: true,                  //是否显示关闭按钮
            debug: false,                       //是否使用debug模式
            progressBar: true,                  //是否显示进度条
            positionClass: "toast-top-center",   //弹出窗的位置
            showDuration: "300",                //显示动作时间
            preventDuplicates: true,            //提示框只出现一次
            hideDuration: "300",                //隐藏动作时间
            timeOut: "3000",                    //自动关闭超时时间
            extendedTimeOut: "1000",            ////加长展示时间
            showEasing: "swing",                //显示时的动画缓冲方式
            hideEasing: "linear",               //消失时的动画缓冲方式
            showMethod: "fadeIn",               //显示时的动画方式
            hideMethod: "fadeOut"               //消失时的动画方式
        };
        toastr.error('<?php echo e(session('alert')); ?>');
    </script>
<?php endif; ?>

<?php if(count($errors) > 0): ?>
    <script type="text/javascript">

        toastr.options = {
            closeButton: true,                  //是否显示关闭按钮
            debug: false,                       //是否使用debug模式
            progressBar: true,                  //是否显示进度条
            positionClass: "toast-top-center",   //弹出窗的位置
            showDuration: "300",                //显示动作时间
            preventDuplicates: true,            //提示框只出现一次
            hideDuration: "300",                //隐藏动作时间
            timeOut: "3000",                    //自动关闭超时时间
            extendedTimeOut: "1000",            ////加长展示时间
            showEasing: "swing",                //显示时的动画缓冲方式
            hideEasing: "linear",               //消失时的动画缓冲方式
            showMethod: "fadeIn",               //显示时的动画方式
            hideMethod: "fadeOut"               //消失时的动画方式
        };
        toastr.error('<?php echo e($errors->first()); ?>');
    </script>
<?php endif; ?>
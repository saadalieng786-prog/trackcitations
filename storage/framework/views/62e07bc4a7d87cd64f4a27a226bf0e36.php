<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <div class="chat-wrapper flex">
            <div
                class="group offcanvas 2xl:!left-0 2xl:visible 2xl:shadow-none 2xl:bg-transparent dark:2xl:bg-transparent !w-auto 2xl:relative offcanvas-start chat-offcanvas"
                tabindex="-1"
                id="offcanvas_User_list"
            >
                <div class="offcanvas-header 2xl:!hidden !justify-end">
                    <button
                        data-pc-dismiss="#offcanvas_User_list"
                        class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500"
                    >
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="offcanvas-body !p-0 h-full">
                    <div id="chat-user_list" class="show collapse-horizontal">
                        <div class="chat-user_list w-[310px] ltr:mr-6 ltr:max-2xl:ml-6 rtl:ml-6 rtl:max-2xl:mr-6">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <h5 class="mb-4">
                                        Messages
                                    </h5>
                                </div>
                                <div class="scroll-block h-[calc(100vh_-_480px)] group-[.show]:h-[calc(100vh_-_410px)]">
                                    <div class="card-body !p-0">
                                        <div
                                            class="list-group *:block *:px-[25px] divide-y divide-inherit border-theme-border dark:border-themedark-border"
                                        >
                                            <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('messaging.show', $conversation->id)); ?>" class="list-group-item list-group-item-action p-3">
                                                <div class="flex items-center">
                                                    <div class="chat-avtar relative">
                                                        <img class="rounded-full w-10" src="https://ui-avatars.com/api/?name=<?php echo e($conversation->name); ?>" alt="User image" />
                                                    </div>
                                                    <div class="grow mx-2">
                                                        <h6 class="mb-0">
                                                            <?php echo e($conversation->name); ?>

                                                            <span class="ltr:float-right rtl:float-left text-sm text-muted f-w-400"><?php echo e(\Carbon\Carbon::parse($conversation->updated_at)->diffForHumans()); ?></span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body !p-0">
                                    <div
                                        class="list-group *:block *:px-[25px] *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border"
                                    >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-content flex-auto min-w-[1%]">
                <div class="card mb-0">
                    <div class="card-header !p-3">
                        <div class="flex items-center">
                            <ul class="flex items-center *:mr-1 ltr:mr-auto rtl:ml-auto mb-0">
                                <li class="list-inline-item align-bottom">
                                    <a
                                        href="#"
                                        class="2xl:hidden w-10 h-10 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                        data-pc-toggle="offcanvas"
                                        data-pc-target="#offcanvas_User_list"
                                    >
                                        <i class="ti ti-menu-2 text-lg leading-none"></i>
                                    </a>
                                    <a
                                        href="#"
                                        class="hidden 2xl:inline-flex w-10 h-10 rounded-xl items-center justify-center btn-link-secondary"
                                        data-pc-toggle="collapse"
                                        data-pc-target="#chat-user_list"
                                    >
                                        <i class="ti ti-menu-2 text-lg leading-none"></i>
                                    </a>
                                </li>
                            </ul>
                            <?php if(auth()->user()->isInternalAdmin()): ?>
                            <ul class="flex items-center *:ml-1 ltr:ml-auto rtl:mr-auto mb-0">
                                <li class="list-inline-item">
                                    <a
                                        href="#"
                                        class="2xl:hidden rounded-xl px-2 py-2 inline-flex items-center justify-center btn-primary"
                                        data-pc-toggle="offcanvas"
                                        data-pc-target="#offcanvas_New_chat"
                                    >
                                        <i class="ti ti-plus text-lg leading-none"></i>
                                        New Conversation
                                    </a>
                                    <a
                                        href="#"
                                        class="hidden 2xl:inline-flex px-2 py-2 rounded-xl items-center justify-center btn-primary"
                                        data-pc-toggle="collapse"
                                        data-pc-target="#chat-new_chat"
                                    >
                                        <i class="ti ti-plus text-lg leading-none"></i>
                                        New Conversation
                                    </a>
                                </li>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="scroll-block chat-message h-[calc(100vh_-_480px)]">
                        <?php if(auth()->user()->isInternalAdmin()): ?>
                        <?php endif; ?>
                        <div class="card-body flex flex-col align-middle items-center h-full justify-center">
                            <svg class="pc-icon w-[400px] h-[400px]">
                                <use xlink:href="#custom-message-2"></use>
                            </svg>
                            <span class="text-muted border rounded px-2 py-4">Please select conversation from sidebar to show it's messages.</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(auth()->user()->isInternalAdmin()): ?>
            <div
                class="group offcanvas 2xl:!right-0 2xl:visible 2xl:shadow-none 2xl:bg-transparent dark:2xl:bg-transparent !w-auto 2xl:relative offcanvas-end chat-offcanvas"
                tabindex="-1"
                id="offcanvas_New_chat"
            >
                <div class="offcanvas-header 2xl:!hidden !justify-end">
                    <button
                        data-pc-dismiss="#offcanvas_New_chat"
                        class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500"
                    >
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="offcanvas-body !p-0">
                    <div id="chat-new_chat" class="collapse-horizontal 2xl:hidden">
                        <div class="chat-new_chat w-[300px] ltr:ml-6 ltr:max-2xl:mr-6 rtl:mr-6 rtl:max-2xl:ml-6">
                            <div class="card">
                                <div class="text-center card-body position-relative pb-0">
                                    <h5 class="text-start">Create New Conversation</h5>
                                    <div class="absolute ltr:right-0 rtl:left-0 top-0 p-[25px] hidden 2xl:inline-flex">
                                        <a
                                            class="w-6 h-6 rounded-xl inline-flex items-center justify-center btn-link-danger dropdown-toggle arrow-none"
                                            href="#"
                                            data-pc-toggle="collapse"
                                            data-pc-target="#chat-new_chat"
                                        >
                                            <i class="ti ti-x text-lg leading-none"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="scroll-block h-[calc(100vh_-500px)] group-[.show]:h-[calc(100vh_-_415px)]">
                                    <div class="card-body">
                                        <div class="show">
                                            <form id="createConversationForm" method="POST" action="<?php echo e(route('api.conversations.store')); ?>" class="w-full max-w-lg shadow-md rounded px-8 pt-6 pb-8 mb-4">
                                                <?php echo csrf_field(); ?>
                                                <!-- Conversation Name -->
                                                <div class="mb-6">
                                                    <label for="conversationName" class="block text-gray-700 text-sm font-bold mb-2">
                                                        Conversation Name
                                                    </label>
                                                    <input
                                                        type="text"
                                                        id="conversationName"
                                                        name="name"
                                                        class="form-control"
                                                        placeholder="Enter conversation name"
                                                        required
                                                    >
                                                </div>

                                                <!-- Members -->
                                                <div class="mb-6">
                                                    <label for="membersSelect" class="block text-gray-700 text-sm font-bold mb-2">
                                                        Select Members
                                                    </label>
                                                    <select
                                                        class="form-control"
                                                        name="user_id[]"
                                                        id="Addparticipants"
                                                        multiple
                                                    ></select>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="flex items-center justify-between">
                                                    <button
                                                        type="submit"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                                        Create Conversation
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/choices.min.js')); ?>"></script>
    <!-- [Page Specific JS] start -->
    <script>

        // scroll-block
        var tc = document.querySelectorAll('.scroll-block');
        for (var t = 0; t < tc.length; t++) {
            new SimpleBar(tc[t]);
        }
        setTimeout(function () {
            var element = document.querySelector('.chat-content .scroll-block .simplebar-content-wrapper');
            var elementheight = document.querySelector('.chat-content .scroll-block .simplebar-content');
            var off = elementheight.getBoundingClientRect();
            var h = off.height;
            element.scrollTop += h;
        }, 100);


        let participants = document.querySelector('#Addparticipants');
        var participantsChoices = new Choices('#Addparticipants', {
            placeholder: true,
            placeholderValue: 'Select User To Add To Chat',
            removeItemButton: true,
            itemSelectText: '',
            shouldSort: false, // Optional: keeps the order of items as provided
        });
        participantsChoices.setChoices(function () {
            return fetch('<?php echo e(route('api.users.exclude')); ?>')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    return data.map(function (user) {
                        return {
                            value: user.id,
                            label: user.name,
                        }
                    });
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('pre-scripts'); ?>
    <script>


    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/choices.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\messaging\index.blade.php ENDPATH**/ ?>
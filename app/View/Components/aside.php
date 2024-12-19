<?php

$user = $model['user'] ?? null;

?>

<button
        id="asideToggle"
        class="block md:hidden p-3 bg-purple-base text-light-base font-semibold absolute top-20 left-0 rounded-r-lg transform transition-transform"
>
    <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
            class="w-5 h-5"
            fill="currentColor"
    >
        <path
                d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"
        ></path>
    </svg
    >
</button>

<!-- Aside -->
<aside
        id="asideMenu"
        class="fixed top-16 pt-4 md:pt-0 left-0 h-full w-64 transform -translate-x-full transition-transform md:w-1/3 lg:w-1/4 md:translate-x-0 md:static md:block pr-8 border-r border-indigo-100 z-50 bg-dark-base"
>
    <div class="sticky flex flex-col gap-2 text-sm ">
        <h2 class="pl-3 mb-4 text-2xl font-semibold">Pengaturan</h2>
        <?php if ($user["isAdmin"] == 'admin'): ?>
            <a href="/admin/dashboard"
               class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Account Setting
            </a>
            <a
                    href="/admin/liked-posts"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Liked Posts
            </a>
            <a
                    href="/admin/manage-posts"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Manage Posts
            </a>
            <a
                    href="/admin/manage-users"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Manage Users
            </a>
            <a
                    href="/logout"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold bg-red-600 text-light-base rounded-lg">
                Logout
            </a>
        <?php else : ?>
            <a href="/user/dashboard"
               class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Account Setting
            </a>
            <a
                    href="/user/liked-posts"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Liked Posts
            </a>
            <a
                    href="/user/manage-posts"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold">
                Manage Posts
            </a>
            <a
                    href="/logout"
                    class="flex items-center flex-shrink-0 px-3 py-2.5 font-semibold bg-red-600 text-light-base rounded-lg">
                Logout
            </a>
        <?php endif; ?>
    </div>
</aside>

<!-- Script untuk mengontrol tampilan aside dan tombol -->
<script>
    const asideMenu = document.getElementById("asideMenu");
    const asideToggle = document.getElementById("asideToggle");

    asideToggle.addEventListener("click", () => {
        asideMenu.classList.toggle("-translate-x-full");
        asideToggle.classList.toggle("translate-x-64"); // Tombol juga bergeser sejauh 64 (sesuai dengan lebar aside)
    });
</script>
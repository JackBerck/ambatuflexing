<?php
$user = $model["user"] ?? null;
?>
<nav class="shadow-purple-base shadow-md bg-dark-base fixed top-0 w-full z-[999]">
    <div class="max-w-screen-lg flex flex-wrap items-center justify-between mx-auto p-4 xl:px-0 md:py-4">
        <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="/images/favicon.png" class="h-8" alt="AmbatuFlex Logo"/>
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-light-base">
                AmbatuFlex
            </span>
        </a>
        <button
                data-collapse-toggle="navbar-default"
                id="navbar-toggle"
                type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2 text-gray-400 hover:bg-gray-700"
                aria-controls="navbar-default"
                aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 17 14">
                <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15">
                </path>
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border rounded-lg md:flex-row md:space-x-2 rtl:space-x-reverse md:mt-0 md:border-0 bg-gray-800 md:bg-dark-base border-gray-700 gap-2 lg:gap-0">
                <li>
                    <a href="/" class="block py-2 px-3 rounded text-light-base " aria-current="page">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="/about" class="block py-2 px-3 rounded text-light-base " aria-current="page">
                        Tentang Kami
                    </a>
                </li>
                <li>
                    <a href="/upload" class="block py-2 px-3 rounded text-light-base " aria-current="page">
                        Upload
                    </a>
                </li>
                <?php
                if (!$user):?>
                    <li>
                        <a href="/login" class="block py-2 px-3 rounded text-light-base bg-purple-base"
                           aria-current="page">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="/register" class="block py-2 px-3 rounded text-light-base ring-1 ring-purple-base"
                           aria-current="page">
                            Register
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="/user/dashboard" class="rounded text-light-base flex items-center" aria-current="page">
                            <img
                                    src="/images/profiles/<?= $user['photo'] ?? 'default.svg' ?>"
                                    alt="photo profile <?= $user['username'] ?>"
                                    class="w-10 object-cover aspect-square rounded-full "
                            />
                            <span class="block md:hidden ml-2"><?= $user['username'] ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    const navbarToggle = document.getElementById("navbar-toggle");
    const navbarDefault = document.getElementById("navbar-default");

    document.addEventListener("DOMContentLoaded", () => {
        navbarToggle.addEventListener("click", () => {
            const expanded =
                navbarToggle.getAttribute("aria-expanded") === "true" || false;
            navbarToggle.setAttribute("aria-expanded", !expanded);
            navbarDefault.classList.toggle("hidden");
        });
    });
</script>
<?php
$user = $model['user'] ?? [];
?>

<section
        id="profile"
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base relative md:static overflow-hidden"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div class="flex flex-col md:flex-row gap-8">
            <?php include_once __DIR__ . "/../Components/aside.php"; ?>
            <div class="small-font-size lg:min-w-[420px]">
                <form
                        action="/admin/dashboard"
                        class="small-font-size flex flex-col gap-4 mb-4 w-full"
                        enctype="multipart/form-data"
                        method="post"
                >
                    <div class="flex">
                        <img
                                src="/images/profiles/<?= $user['photo'] ?? "default.svg" ?>"
                                alt="Foto profil <?= $user['username'] ?>"
                                class="w-36 aspect-square rounded-full object-cover"
                        />
                        <div class="flex flex-col gap-4 mt-4">
                            <div class="bg-blue-base p-2 rounded-lg block">
                                <label
                                        for="profilePhoto"
                                        class="font-semibold cursor-pointer text-light-base"
                                >
                                    <svg
                                            class="w-5 aspect-square text-light-base"
                                            fill="currentColor"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                    >
                                        <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                                    </svg>
                                </label>
                                <input
                                        type="file"
                                        name="profile"
                                        id="profilePhoto"
                                        class="hidden"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <label for="username" class="block font-semibold mb-2"
                        >Nama lengkap</label
                        >
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                type="text"
                                name="username"
                                id="username"
                                placeholder="Masukkan nama lengkap..."
                                value="<?= $user['username'] ?>"
                        />
                    </div>
                    <div class="">
                        <label for="email" class="block font-semibold mb-2"
                        >Alamat Email</label
                        >
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Masukkan alamat email..."
                                value="<?= $user['email'] ?>"
                                readonly
                        />
                    </div>
                    <div class="">
                        <label for="position" class="block font-semibold mb-2"
                        >Position</label
                        >
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                type="text"
                                name="position"
                                id="position"
                                value="<?= $user['position'] ?? "Malas ngoding mending scroll fesnuk" ?>"
                        />
                    </div>
                    <div class="">
                        <label for="bio" class="block font-semibold mb-2">
                            Bio
                        </label>
                        <textarea name="bio" id="bio" cols="30" rows="4"
                                  class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"><?= $user['bio'] ?? "belum ada isinya nih" ?></textarea>
                    </div>
                    <div class="">
                        <button
                                type="submit"
                                class="bg-purple-base text-white font-bold py-2 px-4 w-full rounded hover:bg-purple-700"
                        >Perbarui Akun
                        </button
                        >
                    </div>
                </form>
                <form
                        action="/admin/dashboard/password"
                        class="flex flex-col gap-4 mb-4 w-full"
                        method="post"
                >
                    <div class="">
                        <label for="password" class="block font-semibold mb-2"
                        >Password</label
                        >
                        <div class="relative">
                            <input
                                    class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                    type="password"
                                    name="oldPassword"
                                    id="password"
                                    placeholder="Masukkan password..."
                            />
                            <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 top-1/2 flex items-center pr-3 -translate-y-1/2"
                            >
                                <svg
                                        class="w-5 h-5 text-dark-base"
                                        fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 576 512"
                                >
                                    <path
                                            d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                    ></path>
                                </svg
                                >
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <label for="new-password" class="block font-semibold mb-2"
                        >Password Baru</label
                        >
                        <div class="relative">
                            <input
                                    class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                    type="password"
                                    name="newPassword"
                                    id="new-password"
                                    placeholder="Masukkan password terbaru..."
                            />
                            <button
                                    type="button"
                                    id="toggleNewPassword"
                                    class="absolute inset-y-0 right-0 top-1/2 flex items-center pr-3 -translate-y-1/2"
                            >
                                <svg
                                        class="w-5 h-5 text-dark-base"
                                        fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 576 512"
                                >
                                    <path
                                            d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                    ></path>
                                </svg
                                >
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <label
                                for="verification-new-password"
                                class="block font-semibold mb-2">Verifikasi Password Baru</label
                        >
                        <div class="relative">
                            <input
                                    class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                    type="password"
                                    name="verification-new-password"
                                    id="verification-new-password"
                                    placeholder="Masukkan ulang password terbaru..."
                            />
                            <button
                                    type="button"
                                    id="toggleVerifyNewPassword"
                                    class="absolute inset-y-0 right-0 top-1/2 flex items-center pr-3 -translate-y-1/2"
                            >
                                <svg
                                        class="w-5 h-5 text-dark-base"
                                        fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 576 512"
                                >
                                    <path
                                            d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                    ></path>
                                </svg
                                >
                            </button>
                        </div>
                    </div>
                    <div class="">
                        <button
                                type="submit"
                                class="bg-purple-base text-white font-bold py-2 px-4 w-full rounded hover:bg-purple-700"
                        >Perbarui Password
                        </button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    const togglePassword = document.getElementById("togglePassword");
    const toggleNewPassword = document.getElementById("toggleNewPassword");
    const toggleVerifyNewPassword = document.getElementById(
        "toggleVerifyNewPassword"
    );
    const passwordInput = document.getElementById("password");
    const newPasswordInput = document.getElementById("new-password");
    const verifyNewPasswordInput = document.getElementById(
        "verification-new-password"
    );
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", function () {
        const type =
            passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
    });

    toggleNewPassword.addEventListener("click", function () {
        const type =
            newPasswordInput.getAttribute("type") === "password"
                ? "text"
                : "password";
        newPasswordInput.setAttribute("type", type);
    });

    toggleVerifyNewPassword.addEventListener("click", function () {
        const type =
            verifyNewPasswordInput.getAttribute("type") === "password"
                ? "text"
                : "password";
        verifyNewPasswordInput.setAttribute("type", type);
    });
</script>

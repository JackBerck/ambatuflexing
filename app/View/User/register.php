<section
        id="login"
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div
                class="flex flex-row-reverse bg-dark-base rounded-lg mx-auto shadow-purple-base shadow-md overflow-hidden"
        >
            <div
                    class="hidden lg:block lg:w-1/2 bg-cover bg-center bg-[url('/images/backgrounds/code.jpg')]"
            >
            </div>
            <div class="w-full p-8 lg:w-1/2">
                <h2 class="title-font-size font-bold mb-2 md:text-center">
                    AmbatuFlex
                </h2>
                <p class="normal-font-size mb-4 md:text-center">Daftar akun baru</p>
                <form action="" class="small-font-size flex flex-col gap-4 mb-4">
                    <div class="">
                        <label for="username" class="block font-semibold mb-2"
                        >Nama Lengkap</label
                        >
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                                type="text"
                                name="username"
                                id="username"
                                placeholder="Masukkan nama lengkap..."
                        />
                    </div>
                    <div class="">
                        <label for="email" class="block font-semibold mb-2">Email</label>
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Masukkan alamat email..."
                        />
                    </div>
                    <div class="">
                        <label for="phoneNumber" class="block font-semibold mb-2"
                        >Nomor Handphone</label
                        >
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                                type="text"
                                id="phoneNumber"
                                name="phoneNumber"
                                placeholder="Masukkan nomor handphone..."
                                inputmode="numeric"
                                pattern="[0-9]*"
                        />
                    </div>
                    <div class="">
                        <div class="flex justify-between">
                            <label for="password" class="block font-semibold mb-2"
                            >Password</label
                            >
                        </div>
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Masukkan password..."
                        />
                    </div>
                    <div class="">
                        <div class="flex justify-between">
                            <label for="verifyPassword" class="block font-semibold mb-2"
                            >Verifikasi Password</label
                            >
                        </div>
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                                type="password"
                                name="verifyPassword"
                                id="verifyPassword"
                                placeholder="Verifikasi password..."
                        />
                    </div>
                    <div class="">
                        <label for="location" class="block font-semibold mb-2"
                        >Alamat</label
                        >
                        <input
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
                                type="text"
                                name="location"
                                id="location"
                                placeholder="Masukkan alamat..."
                        />
                    </div>
                    <div class="">
                        <label
                                class="block mb-2 font-semibold text-dark-base"
                                for="file_input">Upload Foto Profil</label
                        >
                        <input
                                class="block w-full text-sm text-dark-base border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none"
                                aria-describedby="file_input_help"
                                id="file_input"
                                type="file"
                        />
                        <p class="mt-1 text-sm text-gray-500" id="file_input_help">
                            SVG, PNG, JPG or GIF.
                        </p>
                    </div>
                    <div class="">
                        <button
                                type="submit"
                                class="bg-purple-base text-light-base font-bold py-2 px-4 w-full rounded hover:bg-purple-800"
                        >Daftar</button
                        >
                    </div>
                </form>
                <p class="small-font-size text-center">
                    Sudah punya akun?
                    <a
                            href="/signin"
                            class="inline-block py-1 px-2 bg-purple-base text-light-base rounded-md"
                    >Masuk sekarang</a
                    >
                </p>
            </div>
        </div>
    </div>
</section>
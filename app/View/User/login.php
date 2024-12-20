<section
        id="login"
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div
                class="flex bg-dark-base rounded-lg mx-auto shadow-purple-base shadow-md overflow-hidden"
        >
            <div
                    class="hidden lg:block lg:w-1/2 bg-cover bg-center bg-[url('/images/backgrounds/vue-code.jpg')]"
            >
            </div>
            <div class="w-full p-8 lg:w-1/2">
                <h2 class="title-font-size font-bold mb-2 md:text-center">
                    DevFlex
                </h2>
                <p class="normal-font-size mb-4 md:text-center">
                    Welcome back!
                </p>
                <form action="/login" method="post" class="small-font-size flex flex-col gap-4 mb-4">
                    <div class="">
                        <label class="block font-semibold mb-2" for="email">Email</label>
                        <input
                                id="email"
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                type="email"
                                placeholder="Enter your email address..."
                                name="email"
                        />
                    </div>
                    <div class="">
                        <div class="flex justify-between">
                            <label class="block font-semibold mb-2" for="password">Password</label>
                            <a href="#" class="text-xs">Forget Password?</a>
                        </div>
                        <input
                                id="password"
                                class="bg-gray-200 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none text-dark-base"
                                type="password"
                                placeholder="Enter your password..."
                                name="password"
                        />
                    </div>
                    <div class="">
                        <button
                                type="submit"
                                class="bg-purple-base text-light-base font-bold py-2 px-4 w-full rounded hover:bg-purple-800"
                        >Login
                        </button
                        >
                    </div>
                </form>
                <p class="small-font-size text-center">
                    Don't have an account?
                    <a
                            href="/register"
                            class="inline-block py-1 px-2 bg-purple-base text-light-base rounded-md"
                    >Register now</a
                    >
                </p>
            </div>
        </div>
    </div>
</section>
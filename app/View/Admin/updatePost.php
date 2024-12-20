<?php
$post = $model["post"] ?? [];
$author = [
    "author" => $model['author'] ?? "",
    "photo" => $model['authorPhoto'] ?? null,
    "position" => $model['authorPosition'] ?? null
];

$images = $model['images'] ?? [];
?>

<section
        id="add-product"
        class="section-padding-x pt-24 pb-8 normal-font-size text-light-base bg-dark-base"
>
    <div class="max-w-screen-lg container">
        <h1 class="font-bold title-font-size mb-4">Update Post</h1>
        <form action="/admin/manage-posts/<?= $post['id'] ?>" method="post">
            <div class="flex flex-col gap-4 mb-4">
                <div class="swiper mySwiper max-w-xl">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image): ?>
                            <div class="swiper-slide">
                                <img
                                        src="/images/posts/<?= $image ?>"
                                        alt="image of <?= $post["title"] ?>"
                                        class="w-full object-cover rounded-lg aspect-video"
                                />
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div>
                    <label for="name" class="block mb-2 font-medium"
                    >Title <span class="text-red-600">*</span></label
                    >
                    <input
                            type="text"
                            name="title"
                            id="name"
                            class="bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter product name..."
                            value="<?= $post["title"] ?>"
                            required
                    />
                </div>
                <div>
                    <label for="name" class="block mb-2 font-medium"
                    >Category <span class="text-red-600">*</span></label
                    >
                    <input
                            type="text"
                            name="category"
                            id="name"
                            class="bg-gray-50 border border-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter product category..."
                            value="<?= $post["category"] ?>"
                            required
                    />
                </div>
                <div class="sm:col-span-2">
                    <label
                            for="description"
                            class="block mb-2 font-medium">Description <span class="text-red-600">*</span></label
                    >
                    <textarea
                            id="description"
                            rows="4"
                            name="content"
                            class="block p-2.5 w-full bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Write product description..."><?= $post['content'] ?></textarea>
                </div>
            </div>
            <button
                    type="submit"
                    class="text-light-base bg-purple-base inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg px-5 py-2.5 text-center"
            >
                <svg
                        class="mr-1 -ml-1 w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                            fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"></path>
                </svg
                >
                Update Content
            </button>
        </form>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper(".mySwiper", {
        pagination: {
            clickable: true,
            el: ".swiper-pagination",
        },
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
</script>
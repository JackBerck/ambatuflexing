<?php

$user = $model["profile"] ?? [];
$posts = $model["posts"] ?? [];
$total = $model["total"] ?? 0;

$perPage = $model["limit"] ?? 20; // Jumlah resep per halaman
$totalPages = ceil($total / $perPage); // Hitung total halaman
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ambil halaman saat ini
$currentPage = max(1, min($totalPages, $currentPage)); // Validasi halaman saat ini

// Function to build pagination URL
function buildPaginationUrl($page)
{
    $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
    parse_str($parsedUrl['query'] ?? '', $queryParams);
    unset($queryParams['page']); // Hapus parameter 'page' jika ada
    $queryParams['page'] = $page; // Tambahkan parameter 'page' baru
    $newQuery = http_build_query($queryParams); // Bangun kembali query string
    return $parsedUrl['path'] . ($newQuery ? '?' . $newQuery : ''); // Gabungkan kembali

}

?>

<section
        id="user"
        class="section-padding-x pt-28 pb-24 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-gray-800 normal-font-size min-h-[480px] md:min-h-[540px] xl:min-h-[640px]"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div class="flex flex-col md:flex-row justify-between gap-4 md:gap-8">
            <div class="w-full md:w-1/3 lg:w-1/4 flex gap-4 md:block">
                <img
                        src="/images/profiles/<?= $user["photo"] ?? 'default.svg' ?>"
                        alt="<?= $user["username"] ?> Photo Profile"
                        class="w-[128px] md:w-[448px] lg:w-[512px] mx-auto rounded-full object-cover aspect-square mb-4"
                />
                <div class="">
                    <h1 class="subtitle-font-size font-bold md:text-center">
                        <?= $user["username"] ?>
                    </h1>
                    <p class="md:text-center"><?= $user["position"] ?></p>
                    <p class="small-font-size text-justify mb-1">
                        <?= $user["bio"] ?>
                    </p>
                    <a
                            href="mailto:<?= $user['email'] ?>"
                            class="small-font-size underline flex items-center gap-2"
                    >
                        <svg
                                fill="currentColor"
                                class="text-light-base w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 512"
                        >
                            <path
                                    d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"
                            ></path>
                        </svg
                        >
                        <?= $user["email"] ?>
                    </a>
                </div>
            </div>
            <?php if(count($posts) === 0): ?>
                <div class="w-full md:w-2/3 lg:w-3/4">
                    <div class="flex justify-center items-center h-full">
                        <p class="text-center">There's no posts yet</p>
                    </div>
                </div>
            <?php else: ?>
            <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 w-full md:w-2/3 lg:w-3/4"
            >
                <?php foreach ($posts as $post): ?>
                    <div class="shadow-sm shadow-purple-base rounded-lg p-2 relative max-h-fit" >
                        <a href="/post/<?= $post['id'] ?>">
                            <img
                                    src="/images/posts/<?= $post["banner"] ?>"
                                    alt="<?= $user["username"] ?>"
                                    class="rounded-md w-full aspect-video object-cover mb-2"
                            />
                        </a>
                        <div class="flex gap-2 items-center mb-2">
                            <img
                                    src="/images/profiles/<?= $post["authorPhoto"] ?>"
                                    alt="<?= $user["username"] ?> Photo Profile"
                                    class="w-8 md:w-10 aspect-square rounded-full object-cover"
                            />
                            <div class="">
                                <h6 class="normal-font-size font-bold"><?= $user["username"] ?></h6>
                                <p class="small-font-size"><?= $user["position"] ?></p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <a href="/post/<?= $post['id'] ?>">
                                <h2 class="normal-font-size font-bold title_card-post truncate-title-card">
                                    <?= truncateText($post["title"], 30) ?>
                                </h2>
                            </a>
                            <p class="small-font-size description_card-post truncate-description-card">
                                <?= truncateText($post["content"] . "", 40) ?>
                            </p>
                        </div>
                        <div class="flex justify-end items-center gap-2">
                            <p class="small-font-size"><?= timeAgo($post["createdAt"]) ?></p>
                            <button class="flex items-center gap-2">
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                        class="w-4 aspect-square text-light-base"
                                        fill="currentColor"
                                >
                                    <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20-.1-.1s0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/>
                                </svg>
                                <span class="normal-font-size"><?= $post["likeCount"] ?></span>
                            </button>
                            <button class="flex items-center gap-2">
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512"
                                        class="w-4 aspect-square text-light-base"
                                        fill="currentColor"
                                >
                                    <path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z"/>
                                </svg>
                                <span class="normal-font-size"><?= $post["commentCount"] ?></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

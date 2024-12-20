<?php
$posts = $model["posts"] ?? [];
$total = $model["total"] ?? 0;

$perPage = $model["limit"] ?? 50;
$totalPages = ceil($total / $perPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));

function buildPaginationUrl($page)
{
    $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
    parse_str($parsedUrl['query'] ?? '', $queryParams);
    unset($queryParams['page']);
    $queryParams['page'] = $page;
    $newQuery = http_build_query($queryParams);
    return $parsedUrl['path'] . ($newQuery ? '?' . $newQuery : '');
}

?>

<section
        id="profile"
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base relative md:static overflow-hidden min-h-[480px] md:min-h-[540px] xl:min-h-[640px]"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div class="flex flex-col md:flex-row gap-8">
            <?php include_once __DIR__ . "/../Components/aside.php"; ?>
            <?php if ($total <= 0): ?>
                <h2>No posts have been uploaded</h2>
            <?php endif; ?>
            <div class="">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($posts as $post): ?>
                        <div class="shadow-sm shadow-purple-base rounded-lg p-2 relative">
                            <div class="flex gap-2 items-center absolute top-2 right-2">
                                <a href="/user/manage-posts/<?= $post['id'] ?>" class="bg-purple-base p-2 rounded-full">
                                    <svg
                                            class="w-5 aspect-square text-light-base"
                                            fill="currentColor"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                    >
                                        <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                                    </svg>
                                </a>
                                <form action="/user/manage-posts" method="post" class="deletePost">
                                    <input type="hidden" name="postId" value="<?= $post['id'] ?>">
                                    <button class="bg-red-base p-2 rounded-full">
                                        <svg
                                                class="w-5 aspect-square text-light-base"
                                                fill="currentColor"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 448 512"
                                        >
                                            <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <a href="">
                                <img
                                        src="/images/posts/<?= $post['banner'] ?>"
                                        alt="<?= $post['title'] ?> Banner Photo"
                                        class="rounded-md w-full aspect-video object-cover mb-2"
                                />
                            </a>
                            <div class="flex gap-2 items-center mb-2">
                                <img
                                        src="/images/profiles/<?= $post['authorPhoto'] ?? "default.svg" ?>"
                                        alt="<?= $post['author'] ?> Photo Profile"
                                        class="w-8 md:w-10 aspect-square rounded-full object-cover"
                                />
                                <div class="">
                                    <h6 class="normal-font-size font-bold"><?= $post['author'] ?></h6>
                                    <p class="small-font-size"><?= $post['authorPosition'] ?></p>
                                </div>
                            </div>
                            <div class="mb-2">
                                <a href="/post/<?= $post['id'] ?>">
                                    <h2 class="normal-font-size font-bold title_card-post">
                                        <?= truncateText($post['title'], 30) ?>
                                    </h2>
                                </a>
                                <p class="small-font-size description_card-post">
                                    <?= truncateText($post['content'], 40) ?>
                                </p>
                            </div>
                            <div class="flex justify-end items-center gap-2">
                                <p class="small-font-size"><?= timeAgo($post['createdAt']) ?></p>
                                <button class="flex items-center gap-2">
                                    <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                            class="w-4 aspect-square text-light-base"
                                            fill="currentColor"
                                    >
                                        <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20-.1-.1s0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/>
                                    </svg>
                                    <span class="normal-font-size"><?= $post['likeCount'] ?></span>
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
                                    <span class="normal-font-size"><?= $post['commentCount'] ?></span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="flex justify-center items-center gap-2 my-4">
                    <?php if ($currentPage > 1): ?>
                        <a
                                href="<?= buildPaginationUrl($currentPage - 1) ?>"
                                class="font-medium text-blue-base hover:underline"
                        >Previous</a
                        >
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a
                                href="<?= buildPaginationUrl($i) ?>"
                                class="font-medium <?= $i === $currentPage ? 'text-blue-base' : 'text-blue-base' ?> hover:underline"
                        ><?= $i ?></a
                        >
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <a
                                href="<?= buildPaginationUrl($currentPage + 1) ?>"
                                class="font-medium text-blue-base hover:underline"
                        >Next</a
                        >
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function alertConfirm() {
        return Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this Post!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {
            return result.isConfirmed;
        });
    }

    document.querySelectorAll(".deletePost").forEach((e) => {
        e.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (await alertConfirm()) {
                e.target.submit();
            }
        })
    });
</script>
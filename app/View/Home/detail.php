<?php

$currentUser = $model["user"] ?? null;

$detail = $model['post'] ?? [];
$isCurrentPostLiked = $model['isCurrentPostLiked'] ?? false;
$author = [
    'username' => $model['author'] ?? "",
    'photo' => $model['authorPhoto'] ?? "",
    'position' => $model['authorPosition'] ?? "",
];
$images = $model['images'] ?? [];
$comments = $model['comments'] ?? [];
$commentCount = $model['commentCount'] ?? 0;
$likeCount = $model['likeCount'] ?? 0;

?>


<section
        id="detail"
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div class="flex flex-col gap-4">
            <!-- Content Start -->
            <div class="">
                <div class="flex gap-2 items-center mb-2">
                    <img src="/images/profiles/<?= $author['photo'] ?? 'default.svg' ?>"
                         alt="<?= $author['username'] ?> Profile Photo"
                         class="w-8 md:w-10 aspect-square object-cover rounded-full"/>
                    <div class="">
                        <p class="normal-font-size"><?= $author['username'] ?></p>
                        <p class="small-font-size"><?= $author['position'] ?></p>
                    </div>
                </div>
                <h1 class="title-font-size font-bold mb-2">
                    <?= $detail['title'] ?>
                </h1>
                <p class="normal-font-size">
                    <?= $detail['content'] ?>
                </p>
                <div class="flex items-center gap-2 mt-4">
                    <p
                            class="bg-light-base text-dark-base small-font-size rounded-sm px-2 py-1 font-bold"
                    >
                        <?= $detail['category'] ?>
                    </p>
                </div>
            </div>
            <!-- Content End -->
            <!-- Photos Start -->
            <div class="flex flex-col">
                <?php if (count($images) === 1): ?>
                    <!-- Tampilan jika hanya ada satu gambar -->
                    <div class="w-full">
                        <img
                                src="/images/posts/<?= $images[0] ?>"
                                alt="<?= $detail['title'] ?>"
                                class="w-full h-auto rounded-lg object-cover aspect-video max-h-[256px]"
                        />
                    </div>
                <?php else: ?>
                    <!-- Grid layout untuk beberapa gambar -->
                    <div class="grid <?= count($images) === 2 ? 'grid-cols-2' : (count($images) === 3 ? 'grid-cols-2' : 'grid-cols-2 lg:grid-cols-4') ?> gap-2">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="<?= (count($images) === 3 && $index === 2) ? 'col-span-2' : 'col-span-1' ?> relative overflow-hidden">
                                <img
                                        src="/images/posts/<?= $image ?>"
                                        alt="<?= $image ?>"
                                        class="w-full h-full object-cover rounded-lg aspect-video max-h-[256px]"
                                />
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Photos End -->
            <div class="flex justify-end gap-4">
                <?php if ($isCurrentPostLiked): ?>
                    <form action="/user/liked-posts?redirect=/post/<?= $detail['id'] ?>" method="post"
                          class="flex items-center gap-2">
                        <input type="hidden" name="postId" value="<?= $detail['id'] ?>">
                        <button type="submit">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512"
                                    class="w-6 aspect-square text-light-base"
                                    fill="currentColor"
                            >
                                <rect x="0" y="0" width="100%" height="100%" fill="pink"/>

                                <path
                                        d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20-.1-.1s0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"
                                ></path>
                            </svg>
                        </button>
                        <span class="normal-font-size"><?= $likeCount ?></span>
                    </form>
                <?php else: ?>
                    <form action="/post/<?= $detail['id'] ?>" method="post" class="flex items-center gap-2">
                        <button type="submit">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512"
                                    class="w-6 aspect-square text-light-base"
                                    fill="currentColor"
                            >
                                <path
                                        d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8l0-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5l0 3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20-.1-.1s0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5l0 3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2l0-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"
                                ></path>
                            </svg>
                        </button>
                        <span class="normal-font-size"><?= $likeCount ?></span>
                    </form>
                <?php endif; ?>
                <a href="#commentarSection" class="flex items-center gap-2">
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"
                            class="w-6 aspect-square text-light-base"
                            fill="currentColor"
                    >
                        <path
                                d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z"
                        ></path>
                    </svg>
                    <span class="normal-font-size"><?= $commentCount ?></span>
                </a>
            </div>
            <!-- Comments Start -->
            <div class="" id="commentarSection">
                <h2 class="subtitle-font-size font-bold mb-2">Komentar</h2>
                <?php if ($currentUser): ?>
                    <form action="/post/<?= $detail["id"] ?>/comment" method="post" class="mb-4">
                        <div class="flex gap-4 mb-4">
                            <img src="/images/profiles/<?= $currentUser['photo'] ?? 'default.svg' ?>"
                                 alt="<?= $currentUser['username'] ?? "" ?> Profile Photo"
                                 class="w-10 h-10 aspect-square object-cover rounded-full"
                            />
                            <div class="w-full">
                        <textarea
                                class="bg-light-base focus:outline-none focus:shadow-outline border border-purple-base rounded py-2 px-4 block w-full appearance-none"
                                rows="3"
                                placeholder="Tulis komentar..."
                                name="comment"></textarea>
                            </div>
                        </div>
                        <button type="submit"
                                class="bg-purple-base text-light-base font-bold py-2 px-4 w-full rounded hover:bg-purple-800">
                            Kirim
                        </button>
                    </form>
                <?php endif; ?>
                <div class="flex gap-4 flex-col">
                    <?php
                    if (count($comments) <= 0) {
                        echo "No comments yet.";
                    }

                    foreach ($comments as $comment): ?>
                        <div class="shadow-purple-base shadow-sm p-2 rounded-sm relative">
                            <div class="flex items-center gap-2 mb-2">
                                <img src="/images/profiles/<?= $comment['photo'] ?? "default.svg" ?>"
                                     alt="comment.name Profile Photo"
                                     class="w-8 md:w-10 aspect-square object-cover rounded-full"/>
                                <div class="">
                                    <p class="normal-font-size"><?= $comment["username"] ?></p>
                                    <p class="small-font-size"><?= $comment["position"] ?></p>
                                </div>
                            </div>
                            <div class="flex justify-between ">
                                <p class="normal-font-size mb-1"><?= $comment["comment"] ?></p>
                                <p class="small-font-size"><?= timeAgo($comment["createdAt"]) ?></p>
                            </div>
                            <?php if ($currentUser['id'] == $comment["userId"]): ?>
                                <form action="/post/<?= $detail['id'] ?>/comment/delete" method="post"
                                      class="absolute top-0 right-0 deleteComment">
                                    <input type="hidden" name="commentId" value="<?= $comment['id'] ?>">
                                    <button type="submit" class="absolute top-0 right-0 p-2">
                                        <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="text-red-base w-5 h-5"
                                                fill="currentColor"
                                                viewBox="0 0 448 512"
                                        >
                                            <path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Comments End -->
        </div>
</section>
<script>
    function alertConfirm() {
        return Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this comment!",
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

    document.querySelectorAll(".deleteComment").forEach((e) => {
        e.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (await alertConfirm()) {
                e.target.submit();
            }
        })
    });
</script>
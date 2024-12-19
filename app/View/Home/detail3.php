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
            <div class="swiper mySwiper max-w-xl">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image): ?>
                        <div class="swiper-slide">
                            <img
                                    src="/images/posts/<?= $image ?>"
                                    alt="image of <?= $detail["title"] ?>"
                                    class="w-full object-cover rounded-lg aspect-video"
                            />
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
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
            </div>
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
                                    <button type="submit">X</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
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

<style>
    .swiper {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
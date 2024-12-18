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
        id="home"
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div class="flex flex-col md:flex-row gap-8">
            <?php include_once __DIR__ . "/../Components/aside.php"; ?>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                    <thead class="small-font-size uppercase bg-gray-700 text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">User</th>
                        <th scope="col" class="px-6 py-3">Title</th>
                        <th scope="col" class="px-6 py-3">Content</th>
                        <th scope="col" class="px-6 py-3">Tag</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr
                                class="odd:bg-gray-900 even:bg-gray-800 border-b border-gray-700"
                        >
                            <th scope="row" class="px-6 py-4"
                            ><img
                                        src="/images/posts/<?= $post['banner'] ?>"
                                        alt="<?= $post['title'] ?>"
                                        class="w-12 rounded-full aspect-square object-cover"
                                /></th
                            >
                            <td
                                    class="px-6 py-4 font-medium text-light-base whitespace-nowrap truncate-title-table"
                            ><?= truncateText($post['title'], 30) ?>
                            </td
                            >
                            <td class="px-6 py-4 truncate-content-table"
                            ><?= truncateText($post['content'], 40) ?>
                            </td
                            >
                            <td class="px-6 py-4 truncate-tag-table"><?= $post["category"] ?></td>
                            <td class="px-6 py-4">
                                <a href="/admin/manage-posts/<?= $post['id'] ?>"
                                   class="font-medium text-blue-base hover:underline"
                                >Edit</a
                                >
                                <form action="/admin/manage-posts" method="post" class="deletePost">
                                    <input type="hidden" name="postId" value="<?= $post['id'] ?>">
                                    <button class="font-medium text-red-base hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Tambahkan pagination di sini -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= buildPaginationUrl($currentPage - 1) ?>"
                                   aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= buildPaginationUrl($i) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1) ?>"
                                   aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- Pagination ends here -->
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
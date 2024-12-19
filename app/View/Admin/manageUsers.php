<?php
$users = $model['manageUsers'] ?? [];
$total = $model["totalUsers"] ?? [];

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
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">Profile</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Position</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user) : ?>
                        <?php if ($user["isAdmin"] == 'user'): ?>
                            <tr
                                    class="odd:bg-gray-900 even:bg-gray-800 border-b border-gray-700"
                            >
                                <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-light-base whitespace-nowrap"
                                ><a href="/profile/<?= $user["id"] ?>">
                                        <?= $user['username'] ?>
                                    </a>
                                </th
                                >
                                <td class="px-6 py-4"
                                ><img
                                            src="/images/profiles/<?= $user['photo'] ?? 'default.svg' ?>"
                                            alt="profile image of <?= $user['username'] ?>"
                                            class="w-12 rounded-full aspect-square object-cover"
                                    /></td
                                >
                                <td class="px-6 py-4"><?= $user['email'] ?></td>
                                <td class="px-6 py-4"><?= $user['position'] ?>r</td>
                                <td class="px-6 py-4">
                                    <a href="/admin/manage-users/<?= $user["id"] ?>"
                                       class="font-medium text-blue-base hover:underline"
                                    >Edit</a
                                    >
                                </td>
                            </tr>
                        <?php endif; ?>
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


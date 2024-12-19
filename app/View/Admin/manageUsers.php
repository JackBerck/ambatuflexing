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
        class="section-padding-x pt-24 pb-12 lg:pt-36 lg:pb-16 normal-font-size text-light-base bg-dark-base relative md:static overflow-hidden"
>
    <div class="container max-w-screen-sm lg:max-w-screen-lg">
        <div class="flex flex-col md:flex-row gap-8">
            <?php include_once __DIR__ . "/../Components/aside.php"; ?>

            <div>
                <form method="get">
                    <input type="text" name="email">
                    <input type="text" name="username">
                    <input type="text" name="position">
                </form>
            </div>

            <table>
                <tr>
                    <th>id</th>
                    <th>email</th>
                    <th>username</th>
                    <th>position</th>
                    <th>photo</th>
                    <th>registered</th>
                    <th>action</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <?php if ($user["isAdmin"] == 'user'): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['position'] ?></td>
                            <td><img src="/images/profiles/<?= $user['photo'] ?? 'default.svg' ?>"
                                     alt="profile image of <?= $user['username'] ?>"></td>
                            <td><?= timeAgo($user['createdAt']) ?></td>
                            <td>
                                <a href="/admin/manage-users/<?= $user["id"] ?>">update</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
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
                            <a class="page-link" href="<?= buildPaginationUrl($currentPage + 1) ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <!-- Pagination ends here -->

        </div>
    </div>
</section>


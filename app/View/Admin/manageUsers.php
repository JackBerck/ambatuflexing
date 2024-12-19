<?php
$users = $model['manageUsers'] ?? [];
$total = $model["totalUsers"] ?? [];

$page = ($total / 50);

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
                </tr>
                <?php foreach ($users as $user): ?>
                    <?php if ($user["isAdmin"] == 'user'): ?>
                        <tr>
                            <th><?= $user['id'] ?></th>
                            <th><?= $user['email'] ?></th>
                            <th><?= $user['username'] ?></th>
                            <th><?= $user['position'] ?></th>
                            <th><?= '/images/profiles/' . $user['photo'] ?? 'default.svg' ?></th>
                            <th><?= timeAgo($user['createdAt']) ?></th>
                        </tr>
                    <?php endif;
                endforeach; ?>

            </table>


        </div>
    </div>
</section>

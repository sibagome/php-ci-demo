<?php
require __DIR__.'/../vendor/autoload.php';

// ヘッダー出力
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ja" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI/CD DEMO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- ナビゲーションバー -->
        <nav class="bg-indigo-600">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a href="#" class="bg-indigo-700 text-white rounded-md px-3 py-2 text-sm font-medium">Users</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ヘッダー -->
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">CI/CD DEMO</h1>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="p-6">
                            <?php
                            try {
                                $dsn = "mysql:host=db;dbname=dev_db;charset=utf8mb4";
                                $pdo = new PDO($dsn, 'user', 'password');
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $stmt = $pdo->query("SELECT * FROM users");
                                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // ステータスメッセージ
                                echo '<div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">';
                                echo '<div class="flex">';
                                echo '<div class="flex-shrink-0">';
                                echo '<i class="fas fa-info-circle text-green-400"></i>';
                                echo '</div>';
                                echo '<div class="ml-3">';
                                echo '<p class="text-sm text-green-700">Showing ' . count($users) . ' users</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';

                                // ユーザーテーブル
                                echo '<div class="overflow-x-auto">';
                                echo '<table class="min-w-full divide-y divide-gray-300">';
                                echo '<thead class="bg-gray-50">';
                                echo '<tr>';
                                echo '<th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">ID</th>';
                                echo '<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name</th>';
                                echo '<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>';
                                echo '<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created At</th>';
                                echo '<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actions</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody class="divide-y divide-gray-200 bg-white">';
                                
                                foreach ($users as $user) {
                                    echo '<tr>';
                                    echo '<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">' . htmlspecialchars($user['id']) . '</td>';
                                    echo '<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">' . htmlspecialchars($user['name']) . '</td>';
                                    echo '<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">' . htmlspecialchars($user['email']) . '</td>';
                                    echo '<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">' . htmlspecialchars($user['created_at']) . '</td>';
                                    echo '<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">';
                                    echo '<button class="text-indigo-600 hover:text-indigo-900 mr-2"><i class="fas fa-edit"></i></button>';
                                    echo '<button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                
                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';

                            } catch (PDOException $e) {
                                echo '<div class="rounded-md bg-red-50 p-4">';
                                echo '<div class="flex">';
                                echo '<div class="flex-shrink-0">';
                                echo '<i class="fas fa-times-circle text-red-400"></i>';
                                echo '</div>';
                                echo '<div class="ml-3">';
                                echo '<h3 class="text-sm font-medium text-red-800">Error</h3>';
                                echo '<p class="text-sm text-red-700 mt-2">Connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- フッター -->
    <footer class="bg-white border-t border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">© 2024 CI/CD DEMO. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
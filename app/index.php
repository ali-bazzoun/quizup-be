<?php

require_once __DIR__ . '/src/database/migrations.php';
require_once __DIR__ . '/src/repositories/UserRepository.php';

$repo = new UserRepository();

// // // 🧪 1. Create a user
// echo "<h3>Create User</h3>";
// $created = $repo->create([
//     'email' => 'testuser@example.com',
//     'password_hash' => password_hash('secret123', PASSWORD_DEFAULT)
// ]);
// echo $created ? "✅ User created<br>" : "❌ Failed to create user<br>";

// 🧪 2. Find by email
echo "<h3>Find by Email</h3>";
$user = $repo->find_by_email('testuser@example.com');
if ($user) {
    echo "✅ Found: ID = {$user->id}, Email = {$user->email}<br>";
} else {
    echo "❌ User not found<br>";
}

// 🧪 3. Update password
echo "<h3>Update Password</h3>";
if ($user) {
    $updated = $repo->update_password($user->id, password_hash('newpassword456', PASSWORD_DEFAULT));
    echo $updated ? "✅ Password updated<br>" : "❌ Failed to update password<br>";
}

// 🧪 4. Delete user
echo "<h3>Delete User</h3>";
if ($user) {
    $deleted = $repo->delete($user->id);
    echo $deleted ? "✅ User deleted<br>" : "❌ Failed to delete user<br>";
}

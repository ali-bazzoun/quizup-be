<?php

require_once __DIR__ . '/src/database/create_tables.php';
require_once __DIR__ . '/src/repositories/QuizRepository.php';

$repo = new QuizRepository();

// // // 🧪 1. Create
// echo "<h3>Create</h3>";
// $created = $repo->create([
//     'title' => 'linear algebra',
//     'quiz_description' => 'Non aute ea adipisicing culpa officia laborum.'
// ]);
// echo $created ? "✅ User created<br>" : "❌ Failed to create user<br>";

// 🧪 2. Find
echo "<h3>Find</h3>";
$find = $repo->find(2);
if ($find) {
    echo "✅ Found: ID = {$find->id}, Description = {$find->quiz_description}<br>";
} else {
    echo "❌ User not found<br>";
}

// // 🧪 3. Update 
// echo "<h3>Update</h3>";
// $updated = $repo->update(2, ['image_path' => '/some/thing']);
// echo $updated ? "✅ updated<br>" : "❌ Failed to update<br>";

// // 🧪 4. Delete
// echo "<h3>Delete</h3>";
// $deleted = $repo->delete(1);
// echo $deleted ? "✅ deleted<br>" : "❌ Failed to delete<br>";


-- Insert sample notifications data
-- First, let's create the notifications table if not exists
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_is_read_index` (`user_id`, `is_read`),
  KEY `notifications_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (assuming user_id 13 exists and has created recipes)
INSERT INTO `notifications` (`user_id`, `from_user_id`, `recipe_id`, `type`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(13, 1, 1, 'recipe_favorited', 'Resep Difavoritkan', 'SiRizky menambahkan resep anda ke favorit', 0, NOW(), NOW()),
(13, 2, 1, 'recipe_favorited', 'Resep Difavoritkan', 'NIGHTSIDE menambahkan resep anda ke favorit', 0, NOW(), NOW()),
(13, 3, 2, 'recipe_favorited', 'Resep Difavoritkan', 'CRHOEX menambahkan resep anda ke favorit', 0, NOW(), NOW()),
(13, 4, 2, 'recipe_favorited', 'Resep Difavoritkan', 'Rpn_03 menambahkan resep anda ke favorit', 0, NOW(), NOW()),
(13, 5, 3, 'recipe_favorited', 'Resep Difavoritkan', 'raihansukablabla menambahkan resep anda ke favorit', 0, NOW(), NOW());

INSERT INTO `types` (`id`, `type`, `class_name`) VALUES (1, 'Текст', 'text'),(2, 'Цитата', 'quote'),(3, 'Фото', 'photo'),(4, 'Видео', 'video'),(5, 'Ссылка', 'link'); /*Добавляем типы данных*/

INSERT INTO `users`(`dt_add`,`email`,`login`,`password`,`avatar_path`,`first_name`,`last_name`) VALUES ('2021-02-10 20:16:14','vlad@test.com','vlad@test.com','$2y$10$xs54vQ2ApCApkfBGJRjU.uhXt3nb8s4iOzHJ67mKRFtlV5imP8uuO','img/userpic.jpg','Влад','Кусков'); /*Добавляем пользователя*/

INSERT INTO `users`(`dt_add`,`email`,`login`,`password`,`avatar_path`,`first_name`,`last_name`) VALUES ('2021-02-10 21:10:14','petr@test.com','petr@test.com','$2y$10$xs54vQ2ApCApkfBGJRjU.uhXt3nb8s4iOzHJ67mKRFtlV5imP8uuO','img/userpic-petr.jpg','Петр','Блоггеров'); /*Добавляем пользователя*/

INSERT INTO `posts`(`user_id`, `type_id`, `publication_date`, `title`, `content`, `count_view`) VALUES (1, 1, '2021-03-01 12:04:02', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', 1); /*Добавляем текстовый пост*/

INSERT INTO `comments` (`publication_date`, `content`, `user_id`, `post_id` ) VALUES ('2021-03-07 18:25:44', 'Жду, не дождусь 6-ой сезон :-)', 1, 1); /*Добавляем комментарий к посту*/

SELECT posts.id, posts.content, posts.title, posts.publication_date, posts.user_id, posts.author_quote, posts.img_path, posts.video_path, type_id, site_path, users.dt_add, users.first_name, users.last_name, users.avatar_path, types.class_name, (SELECT COUNT(id) FROM `likes` WHERE post_id = posts.id) AS likes_count FROM `posts` INNER JOIN `users` ON posts.user_id = users.id INNER JOIN `types` ON posts.type_id = types.id ORDER BY `posts`.`count_view` DESC /*Выводим список с сортировкой по популярности и вместе с именами авторов и типом контента*/

INSERT INTO `likes` (`like_user_id`, `post_id`) VALUES (1, 1)/* Пользователь №1 поставил лайк посту №1 */

INSERT INTO `subscriptions` (`user_id`, `follower_user_id` ) VALUES (1, 2) /* Пользователь №2 подписался на пользователя №1 */
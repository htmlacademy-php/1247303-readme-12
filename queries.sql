INSERT INTO `content_type` VALUES (1,'Текст','post-text'),(2,'Цитата','post-quote'),(3,'Картинка','post-photo'),(4,'Видео','post-video'),(5,'Ссылка','post-link');

INSERT INTO `users` VALUES (1, '2021-03-10 15:11:12', 'tanya@test.com', 'tanya', 123456, 'userpic-tanya.jpg');
INSERT INTO `users` VALUES (2, '2021-02-10 20:16:14', 'vlad@test.com', 'vlad', 654321, 'userpic.jpg');
INSERT INTO `users` VALUES (3, '2020-05-07 10:35:07', 'vitya@test.com', 'victor', 00000, 'userpic-mark.jpg');
INSERT INTO `users` VALUES (4, '2019-07-11 21:48:17', 'michael@mail.com', 'misha', 'qwerty', 'userpic-misha.jpg');

INSERT INTO `posts` VALUES (1, 1, 2, '2021-06-22 21:05:02', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.', 'Неизвестный Автор', NULL, NULL, NULL, 10);
INSERT INTO `posts` VALUES (2, 2, 1, '2021-03-01 12:04:02', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', NULL, NULL, NULL, NULL, 12);
INSERT INTO `posts` VALUES (3, 3, 3, '2021-03-03 20:24:02', 'Наконец, обработал фотки!', NULL, NULL, 'img/rock-medium.jpg', NULL, NULL, 8);


INSERT INTO `comments` VALUES (1, '2021-03-08 13:33:43', 'Отличные фото!', 1, 3);
INSERT INTO `comments` VALUES (2, '2021-03-07 18:25:44', 'Жду, не дождусь 6-ой сезон :-)', 2, 2);

SELECT posts.text, users.login, types.title FROM `posts` LEFT JOIN `users` ON posts.user_id = users.id LEFT JOIN `types` ON posts.type_id = types.id order by `count_view` DESC /*Выводим список с сортировкой по популярности и вместе с именами авторов и типом контента*/
SELECT posts.text FROM `posts` LEFT JOIN `users` ON posts.user_id = users.id WHERE users.id = 1 /*Вывести все посты от пользователя с ID = 1*/
SELECT comments.content FROM `comments` LEFT JOIN `users` ON comments.user_id = users.id WHERE comments.post_id = 2 /* вывести список комментариев для одного поста, в комментариях должен быть логин пользователя */

INSERT INTO `likes` VALUES (1, 2, 3) /* Пользователь №2 поставил лайк посту №3 */
INSERT INTO `subscriptions` VALUES (1, 1, 3) /* Пользователь №1 подписался на пользователя №3 */
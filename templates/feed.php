      <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
      </div>
      <div class="page__main-wrapper container">
        <section class="feed">
          <h2 class="visually-hidden">Лента</h2>
          <div class="feed__main-wrapper">
            <div class="feed__wrapper">
            <?php foreach ($posts as $key => $post):?>
              <article class="feed__post post post-<?=$post['class_name']?>">
                <header class="post__header post__author">
                  <a class="post__author-link" href="profile.php?id=<?=$post['user_id']?>" title="Автор">
                    <div class="post__avatar-wrapper">
                      <img class="post__author-avatar" src="<?=$post['avatar_path']?>" alt="Аватар пользователя" width="60" height="60">
                    </div>
                    <div class="post__info">
                      <b class="post__author-name"><?=$post['first_name'] ." ".  $post['last_name'] ?></b>
                      <span class="post__time"><?=relativeDate($post['publication_date']) . " назад" ?></span>
                    </div>
                  </a>
                </header>
                <div class="post__main">
                  <?php if ($post['class_name'] === 'photo'):?>
                    <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2>
                    <div class="post-photo__image-wrapper">
                      <img src="<?=$post['img_path']?>" alt="Фото от пользователя" width="760" height="396">
                    </div>
                  <?php elseif ($post['class_name'] === 'text'):?>
                    <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2>
                    <p>
                      <?=cutStr($post['content'], $post['id'], 300)?>
                    </p>
                  <?php elseif ($post['class_name'] === 'video'):?>
                    <div class="post-video__preview">
                      <?= embed_youtube_video($post['video_path']) ?>
                    </div>
                    <?php elseif ($post['class_name'] === 'quote'):?>
                      <blockquote>
                        <p>
                          <?=$post['content']?>
                        </p>
                        <cite cite><?=$post['author_quote']?></cite>
                      </blockquote>
                    <?php elseif ($post['class_name'] === 'link'):?>
                      <div class="post-link__wrapper">
                        <a class="post-link__external" href="<?=$post['site_path']?>" title="Перейти по ссылке">
                          <div class="post-link__icon-wrapper">
                            <img src="https://www.google.com/s2/favicons?domain=<?= $post['site_path'] ?>" alt="Иконка">
                          </div>
                          <div class="post-link__info">
                            <h3><?=$post['title']?></h3>
                            <span><?=$post['site_path']?></span>
                          </div>
                          <svg class="post-link__arrow" width="11" height="16">
                            <use xlink:href="#icon-arrow-right-ad"></use>
                          </svg>
                        </a>
                      </div>
                    <?php endif ?>
                </div>

                <footer class="post__footer post__indicators">
                  <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="feed.php?post-id-likes=<?=($post['id']); ?>" title="Лайк">
                      <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                      </svg>
                      <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                      </svg>
                      <span><?=get_count_likes($connection, $post['id']); ?></span>
                      <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--comments button" href="post.php?post-id=<?=($post['id'])?>" title="Комментарии">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                      </svg>
                      <span><?=get_count_comments($connection, $post['id']); ?></span>
                      <span class="visually-hidden">количество комментариев</span>
                    </a>
                    <?php if ((int) $post['user_id'] != (int) $user['id']): ?>
                    <a class="post__indicator post__indicator--repost button" href="feed.php?repost=<?=($post['id'])?>&key=<?=$key?>" title="Репост">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-repost"></use>
                      </svg>
                      <span><?=(get_count_repost($connection, $post['id'])) ? get_count_repost($connection, $post['id']) : "0"?></span>
                      <span class="visually-hidden">количество репостов</span>
                    </a>
                    <?php endif; ?>
                  </div>
                </footer>
              </article>

              <?php endforeach; ?>
            </div>
          </div>
          <ul class="feed__filters filters">
            <li class="feed__filters-item filters__item">
              <a class="filters__button filters__button--active" href="feed.php">
                <span>Все</span>
              </a>
            </li>
            <?php foreach ($types_content as $type):?>
            <li class="feed__filters-item filters__item">
              <a class="filters__button filters__button--<?=$type['class_name']?> button <?= ((int) $type['id'] === $get_id) ? 'filters__button--active' : '' ?>" href="feed.php?categories-id=<?=$type['id'];?>">
                <span class="visually-hidden"><?=$type['type']?></span>
                <svg class="filters__icon" width="22" height="18">
                  <use xlink:href="#icon-filter-<?=$type['class_name']?>"></use>
                </svg>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </section>
        <aside class="promo">
          <article class="promo__block promo__block--barbershop">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
              Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
            </p>
            <a class="promo__link" href="#">
              Подробнее
            </a>
          </article>
          <article class="promo__block promo__block--technomart">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
              Товары будущего уже сегодня в онлайн-сторе Техномарт!
            </p>
            <a class="promo__link" href="#">
              Перейти в магазин
            </a>
          </article>
          <article class="promo__block">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
              Здесь<br> могла быть<br> ваша реклама
            </p>
            <a class="promo__link" href="#">
              Разместить
            </a>
          </article>
        </aside>
      </div>

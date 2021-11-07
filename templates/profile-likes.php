                <h2 class="visually-hidden">Лайки</h2>
                <ul class="profile__likes-list">
                <?php foreach($posts_likes as $post_like): ?>
                  <li class="post-mini post-mini--<?=$post_like['class_name']?> post user">
                    <div class="post-mini__user-info user__info">
                      <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="profile.php?id=<?=$post_like['like_user_id']?>">
                          <img class="post-mini__picture user__picture" src="<?=$post_like['avatar_path']?>" alt="Аватар пользователя">
                        </a>
                      </div>
                      <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="profile.php?id=<?=$post_like['like_user_id']?>">
                          <span><?=$post_like['first_name'] ." ". $post_like['last_name']?></span>
                        </a>
                        <div class="post-mini__action">
                          <span class="post-mini__activity user__additional">Лайкнул публикацию</span>
                          <time class="post-mini__time user__additional" datetime="<?=$post_like['dt_add']?>"><?=relativeDate($post_like['dt_add']) . " назад" ?></time>
                        </div>
                      </div>
                    </div>
                    <div class="post-mini__preview">
                        <?php if($post_like['class_name'] === 'photo'): ?>
                          <a class="post-mini__link" href="post.php?post-id=<?=$post_like['post_id']?>" title="Перейти на публикацию">
                            <div class="post-mini__image-wrapper">
                              <img class="post-mini__image" src="<?=$post_like['img_path']?>" width="109" height="109" alt="Превью публикации">
                            </div>
                            <span class="visually-hidden">Фото</span>
                          </a>
                          <?php elseif($post_like['class_name'] === 'text'): ?>
                            <a class="post-mini__link" href="post.php?post-id=<?=$post_like['post_id']?>" title="Перейти на публикацию">
                              <span class="visually-hidden">Текст</span>
                                <svg class="post-mini__preview-icon" width="20" height="21">
                                  <use xlink:href="#icon-filter-text"></use>
                                </svg>
                            </a>
                            <?php elseif($post_like['class_name'] === 'quote'): ?>
                            <a class="post-mini__link" href="post.php?post-id=<?=$post_like['post_id']?>" title="Перейти на публикацию">
                              <span class="visually-hidden">Цитата</span>
                                <svg class="post-mini__preview-icon" width="20" height="21">
                                  <use xlink:href="#icon-filter-quote"></use>
                                </svg>
                            </a>
                            <?php elseif($post_like['class_name'] === 'link'): ?>
                            <a class="post-mini__link" href="post.php?post-id=<?=$post_like['post_id']?>" title="Перейти на публикацию">
                              <span class="visually-hidden">Цитата</span>
                                <svg class="post-mini__preview-icon" width="20" height="21">
                                  <use xlink:href="#icon-filter-link"></use>
                                </svg>
                            </a>
                            <?php elseif($post_like['class_name'] === 'video'): ?>
                            <a class="post-mini__link" href="post.php?post-id=<?=$post_like['post_id']?>" title="Перейти на публикацию">
                              <div class="post-mini__image-wrapper">
                                <img class="post-mini__image" src="img/coast-small.png" width="109" height="109" alt="Превью публикации">
                                <span class="post-mini__play-big">
                                  <svg svg class="post-mini__play-big-icon" width="12" height="13">
                                    <use xlink:href="#icon-video-play-big"></use>
                                  </svg>
                                </span>
                              </div>
                              <span class="visually-hidden">Видео</span>
                            </a> 
                          <?php endif; ?>
                    </div>
                  </li>
                <?php endforeach; ?> 
                </ul>

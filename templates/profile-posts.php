
                <h2 class="visually-hidden">Публикации</h2>
                <?php foreach ($posts as $key => $post):?>
                <article class="profile__post post post-<?=$post['class_name']?>"> 
                  <header class="post__header">
                    <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2>
                  </header>

                <div class="post__main">
                    <?php if($post['class_name'] === 'photo'):?>
                      <!-- <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2> -->
                      <div class="post-photo__image-wrapper">
                        <img src="<?=$post['img_path']?>" alt="Фото от пользователя" width="760" height="396">
                      </div>
                    <?php elseif($post['class_name'] === 'text'):?>
                      <!-- <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2> -->
                      <p>
                        <?=$post['content']?>
                      </p>
                      <a class="post-text__more-link" href="#">Читать далее</a>
                    <?php elseif($post['class_name'] === 'video'):?>
                      <div class="post-video__preview">
                        <?= embed_youtube_video($post['video_path']) ?>
                      </div>
                      <?php elseif($post['class_name'] === 'quote'):?>
                        <blockquote>
                          <p>
                            <?=$post['content']?>
                          </p>
                          <cite cite><?=$post['author_quote']?></cite>
                        </blockquote>
                      <?php elseif($post['class_name'] === 'link'):?>
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
                      <?php endif; ?>
                </div>

                  <footer class="post__footer">
                    <div class="post__indicators">
                      <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes button" href="profile.php?id=<?=$user_profile['id']?>&post-id-likes=<?=($post['id'])?>" title="Лайк">
                          <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                          </svg>
                          <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                            <use xlink:href="#icon-heart-active"></use>
                          </svg>
                          <span><?=get_count_likes($connection, $post['id']); ?></span>
                          <span class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--repost button" href="profile.php?id=<?=$user_profile['id']?>&tabs=posts&repost=<?=($post['id'])?>&key=<?=$key?>" title="Репост">
                          <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-repost"></use>
                          </svg>
                          <span><?=(get_count_repost($connection,$post['id']))? get_count_repost($connection,$post['id']):"0"?></span>
                          <span class="visually-hidden">количество репостов</span>
                        </a>
                      </div>
                      <time class="post__time" datetime="<?=$post['publictation_date']?>"><?=relativeDate($post['publictation_date']) . " назад" ?></time>
                    </div>
                    <ul class="post__tags">
                      <?php $tags = get_tags_post($connection, $post['id']); foreach($tags as $tag): ?>
                      <li><a href="search.php?tag=<?=$tag['title']?>&id=<?=$tag['id']?>"><?="#" . $tag['title']?></a></li>
                      <?php endforeach ?>
                    </ul>
                  </footer>
                  <?php $comments = get_comments($connection, $post['id']); if(count($comments) && !$view_comment): ?>
                    <div class="comments">
                      <a class="comments__button button" href="profile.php?id=<?=$user_profile["id"]?>&tabs=posts&view=1&comments-post=<?=$post['id']?>">Показать комментарии</a>
                    </div>
                  <?php elseif($view_comment): ?>
                  <div class="comments">
                      <div class="comments__list-wrapper">
                        <ul class="comments__list">
                          <?php if($view_all_comment) {$comments = get_comments($connection, $post['id'], null);}; foreach($comments as $comment): ?>
                          <li class="comments__item user">
                            <div class="comments__avatar">
                              <a class="user__avatar-link" href="profile.php?id=<?=$comment['id']?>">
                                <img class="comments__picture" src="<?=$comment['avatar_path'] ?>" alt="Аватар пользователя">
                              </a>
                            </div>
                            <div class="comments__info">
                              <div class="comments__name-wrapper">
                                <a class="comments__user-name" href="profile.php?id=<?=$comment['id']?>">
                                  <span><?=$comment['first_name']." ".$comment['last_name']?></span>
                                </a>
                                <time class="comments__time" datetime="<?=$comment['publictation_date']?>"><?=relativeDate($comment['publictation_date']) . " назад"?> </time>
                              </div>
                              <p class="comments__text">
                                <?=$comment['content']?>
                              </p>
                            </div>
                          </li>
                        <?php endforeach; ?>
                        </ul>
                        <?php if(get_count_comments($connection, $post['id']) > 2 && !$view_all_comment): ?>
                        <a class="comments__more-link" href="profile.php?id=<?=$user_profile["id"]?>&tabs=posts&view=1&comments-post=<?=$post['id']?>&all=1">
                          <span>Показать все комментарии</span>
                          <sup class="comments__amount"><?=get_count_comments($connection, $post['id'])?></sup>
                        </a>
                        <?php endif; ?>
                      </div>
                  </div>
                  <form class="comments__form form" action="profile.php?id=<?=$user_profile["id"]?>&tabs=posts&view=1&comments-post=<?=$post['id']?>" method="post">
                    <div class="comments__my-avatar">
                      <img class="comments__picture" src="<?=$user['avatar_path']?>" alt="Аватар пользователя">
                    </div>
                    <div class="form__input-section <?=(isset($form_errors["comment-text"])) ? "form__input-section--error":""?>">
                      <textarea class="comments__textarea form__textarea form__input" id="comment-text" name="comment-text" placeholder="Ваш комментарий"><?= ($form_errors && $filter_form_data['comment-text']) ? $filter_form_data['comment-text'] : '' ?></textarea>
                      <label class="visually-hidden">Ваш комментарий</label>
                      <button class="form__error-button button" type="button">!</button>
                      <div div class="form__error-text">
                        <h3 class="form__error-title">Ошибка валидации</h3>
                        <p class="form__error-desc"><?=$form_errors["comment-text"]?></p>
                      </div>
                    </div>
                    <button class="comments__submit button button--green" type="submit">Отправить</button>
                  </form>
                  <?php endif; ?>
                  </article> 
                <?php endforeach; ?> 

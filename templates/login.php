<main class="page__main page__main--login">
      <div class="container">
        <h1 class="page__title page__title--login">Вход</h1>
      </div>
      <section class="login container">
        <h2 class="visually-hidden">Форма авторизации</h2>
        <form class="login__form form" action="#" method="post">
          <div class="login__input-wrapper form__input-wrapper">
            <label class="login__label form__label" for="login-email">Электронная почта</label>
            <div class="form__input-section <?=(isset($form_errors["email"])) ? "form__input-section--error" : ""?>">
              <input class="login__input form__input" id="login-email" type="email" name="email" placeholder="Введите e-mail" value="<?= ($form_errors && $filter_form_data["email"]) ? $filter_form_data["email"] : '' ?>">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=(isset($form_errors["email"])) ? "{$form_errors["email"]}" : ""?></p>
              </div>
            </div>
          </div>
          <div class="login__input-wrapper form__input-wrapper">
            <label class="login__label form__label" for="login-password">Пароль</label>
            <div class="form__input-section <?=(isset($form_errors["password"])) ? "form__input-section--error" : ""?>">
              <input class="login__input form__input" id="login-password" type="password" name="password" placeholder="Введите пароль">
              <button class="form__error-button button button--main" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=(isset($form_errors["password"])) ? "{$form_errors["password"]}" : ""?></p>
              </div>
            </div>
          </div>
          <button class="login__submit button button--main" type="submit">Отправить</button>
        </form>
      </section>
    </main>
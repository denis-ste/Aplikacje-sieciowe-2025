{extends file="layouts/layout.tpl"}

{block name=content}

<h2>Rejestracja</h2>

<form method="post" action="{$conf->action_url}register" class="auth-form">

    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" value="{$form->login|default:''}">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{$form->email|default:''}">
    </div>

    <div class="form-group">
        <label for="password">Hasło</label>
        <input type="password" id="password" name="password">
    </div>

    <div class="form-group">
        <label for="password_repeat">Powtórz hasło</label>
        <input type="password" id="password_repeat" name="password_repeat">
    </div>

    <button type="submit" class="btn-submit">
        Zarejestruj
    </button>

</form>

{/block}
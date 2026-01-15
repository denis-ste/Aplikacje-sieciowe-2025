{extends file="layout.tpl"}

{block name=content}
  <div class="card">
    <h1>Logowanie</h1>

    {include file="templates/messages.tpl"}

    <form method="post" action="{$app_url}/?action=login">
      <input type="hidden" name="return" value="{$form->returnUrl|default:''|escape}">

      <div class="form-row">
        <label>Login:</label>
        <input type="text" name="login" value="{$form->login|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Hasło:</label>
        <input type="password" name="pass" value="">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary btn-block">Zaloguj</button>
      </div>
    </form>
  </div>
{/block}

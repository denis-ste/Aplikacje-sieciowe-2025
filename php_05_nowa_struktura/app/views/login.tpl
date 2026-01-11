{extends file="layout.tpl"}

{block name=content}
  <div class="card">
    <h1>Logowanie</h1>

    <form method="post" action="{$app_url}/?action=login{if $smarty.get.return}&return={$smarty.get.return|escape:'url'}{/if}">
      <div class="form-row">
        <label>Login:</label>
        <input type="text" name="login" value="{$login|escape}">
      </div>

      <div class="form-row">
        <label>Hasło:</label>
        <input type="password" name="pass" value="">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary btn-block">Zaloguj</button>
      </div>
    </form>

    {if $messages|@count > 0}
      <div class="msg msg-err">
        <ul>
          {foreach $messages as $m}
            <li>{$m|escape}</li>
          {/foreach}
        </ul>
      </div>
    {/if}
  </div>
{/block}

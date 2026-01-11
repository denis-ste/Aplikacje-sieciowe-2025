{extends file="layout.tpl"}

{block name=title}Logowanie{/block}

{block name=app_top}
  <div class="hero">
    <div class="hero-inner">
      <div class="hero-box">
        <div class="hero-title">LOGOWANIE</div>
        <div class="hero-sub">Zaloguj się, aby przejść do aplikacji</div>
        <a class="btn" href="#app_content">PRZEJDŹ DO FORMULARZA</a>
      </div>
    </div>
  </div>
{/block}

{block name=app_content}
  <div class="card">
    <div class="h2">Dane testowe</div>
    <div class="muted">admin/admin (uprzywilejowany) oraz user/user (zwykły).</div>

    <form method="post" action="{$app_root}/app/security/login.php#app_content">
      <div class="row">
        <div class="col">
          <label>Login</label>
          <input type="text" name="login" value="{$login|default:''|escape}">
        </div>
        <div class="col">
          <label>Hasło</label>
          <input type="password" name="pass" value="">
        </div>
      </div>

      <div style="margin-top:14px;">
        <button type="submit">Zaloguj</button>
      </div>
    </form>

    {if isset($messages) && $messages|@count > 0}
      <div class="errors" style="margin-top:14px;">
        <ol style="margin:0; padding-left: 18px;">
          {foreach from=$messages item=m}
            <li>{$m|default:''|escape}</li>
          {/foreach}
        </ol>
      </div>
    {/if}
  </div>
{/block}

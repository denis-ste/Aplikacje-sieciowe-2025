{extends file="layout.tpl"}

{block name=content}
  <div class="card">
    <h1>{$page_title|default:'Welcome!'}</h1>
    <h2>{$page_subtitle|default:'Witaj w systemie kalkulatorów'}</h2>
    <div class="center">
      <a class="btn btn-primary" href="{$app_url}/app/security/login.php">Zaloguj się</a>
    </div>
  </div>
{/block}

<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$page_title|default:'Aplikacja'}</title>
  <link rel="stylesheet" href="{$app_url}/css/ui.css">
</head>
<body>
  <div class="topbar">
    <div class="brand">Dzień dobry</div>
    <div class="topbar-right">
      {if $is_logged}
        <a class="toplink" href="{$app_url}/app/security/logout.php">Wyloguj</a>
      {/if}
    </div>
  </div>

  <div class="page">
    {block name=content}
      {*
        Kompatybilność z szablonami, które używają bloków app_top/app_content
        (np. templates/home.tpl dla kalkulatora kredytowego).
      *}
      {block name=app_top}{/block}
      <div id="app_content">
        {block name=app_content}{/block}
      </div>
    {/block}
  </div>
</body>
</html>

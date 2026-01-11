{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>{$page_title|default:'Witaj w systemie kalkulatorów!'}</h1>
    <h2>{$page_subtitle|default:'Wybierz rodzaj kalkulatora:'}</h2>

    <div class="btn-col">
      <a class="btn btn-secondary btn-block" href="{$app_url}/app/calc.php">Zwykły kalkulator</a>
      <a class="btn btn-primary btn-block" href="{$app_url}/app/kredyt.php">Kalkulator kredytowy</a>
      <a class="btn btn-danger btn-block" href="{$app_url}/app/karta_chroniona.php">Karta chroniona</a>
    </div>
  </div>
{/block}

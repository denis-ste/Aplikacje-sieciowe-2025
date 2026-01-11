{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>{$page_title|default:'Witaj w systemie kalkulatorów!'}</h1>
    <h2>{$page_subtitle|default:'Wybierz rodzaj kalkulatora:'}</h2>

    <div class="btn-col">
      <a class="btn btn-secondary btn-block" href="{$app_url}/?action=calc">Zwykły kalkulator</a>
      <a class="btn btn-primary btn-block" href="{$app_url}/?action=kredyt">Kalkulator kredytowy</a>
      <a class="btn btn-danger btn-block" href="{$app_url}/?action=karta">Karta chroniona</a>
    </div>
  </div>
{/block}

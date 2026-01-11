{extends file="layout.tpl"}

{block name=title}{$page_title|default:'Strona główna'}{/block}

{block name=app_top}
  <div class="hero">
    <div class="hero-inner">
      <div class="hero-box">
        <div class="hero-title">PRZYKŁAD 03</div>
        <div class="hero-sub">{$page_subtitle|default:'Smarty: layout + dziedziczenie + kalkulator kredytowy'|escape}</div>
        <a class="btn" href="#app_content">IDŹ DO FORMULARZA</a>
      </div>
    </div>
  </div>
{/block}

{block name=app_content}
  <div class="card">
    <div class="h2">Kalkulator kredytowy</div>
    <div class="muted">Formularz + wynik w Smarty, logika w PHP (assign).</div>

    <div class="footer-actions" style="margin:10px 0 16px;">
      <a class="link" href="{$app_root|default:''}/app/calc.php#app_content">Przejdź do prostego kalkulatora</a>
    </div>

    {include file="partials/kredyt_form.tpl"}
  </div>
{/block}

{block name=scripts}
  {if isset($scroll_to_content) && $scroll_to_content}
    <script>
      if (location.hash !== '#app_content') location.hash = 'app_content';
    </script>
  {/if}
{/block}

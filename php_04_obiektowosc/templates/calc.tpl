{extends file="layout.tpl"}

{block name=title}Kalkulator{/block}

{block name=app_top}
  <div class="hero">
    <div class="hero-inner">
      <div class="hero-box">
        <div class="hero-title">KALKULATOR</div>
        <div class="hero-sub">Prosty kalkulator z ograniczeniami operacji dla roli user.</div>
        <a class="btn" href="#app_content">IDŹ DO FORMULARZA</a>
      </div>
    </div>
  </div>
{/block}

{block name=app_content}
  <div class="card">
    <div class="h2">Prosty kalkulator</div>
    <div class="muted">Dla user: operacje "-" i "/" są zablokowane (tylko manager).</div>

    {if $user_role|default:'' != 'manager'}
      <div class="info" style="margin-top:14px;">
        Nie możesz używać operacji <b>-</b> ani <b>/</b> (dostępne tylko dla administratora).
      </div>
    {/if}

    <form method="post" action="{$app_root|default:''}/app/calc.php#app_content" style="margin-top:14px;">
      <div class="row">
        <div class="col">
          <label>Liczba 1</label>
          <input type="text" name="x" value="{$x|default:''|escape}">
        </div>

        <div class="col">
          <label>Liczba 2</label>
          <input type="text" name="y" value="{$y|default:''|escape}">
        </div>

        <div class="col">
          <label>Operacja</label>
          <select name="op">
            <option value="plus"  {if $operation|default:'plus' == 'plus'}selected{/if}>+</option>
            <option value="minus" {if $operation|default:'plus' == 'minus'}selected{/if}>-</option>
            <option value="times" {if $operation|default:'plus' == 'times'}selected{/if}>*</option>
            <option value="div"   {if $operation|default:'plus' == 'div'}selected{/if}>/</option>
          </select>
        </div>
      </div>

      <div style="margin-top:14px;">
        <button type="submit">Oblicz</button>
      </div>
    </form>

    {if isset($messages) && $messages|@count > 0}
      <div class="errors" style="margin-top:14px;">
        <ol style="margin:0; padding-left: 18px;">
          {foreach from=$messages item=m}
            <li>{$m|escape}</li>
          {/foreach}
        </ol>
      </div>
    {/if}

    {if $result !== null && (!isset($messages) || $messages|@count == 0)}
      <div class="result" style="margin-top:14px;">
        Wynik: <b>{$result|escape}</b>
      </div>
    {/if}

    <div class="footer-actions">
      <a class="link" href="{$app_root|default:''}/index.php#app_top">Start</a>
      <a class="link" href="#app_top">Góra strony</a>
    </div>
  </div>
{/block}

{block name=scripts}
  {if isset($scroll_to_content) && $scroll_to_content}
    <script>
      if (location.hash !== '#app_content') location.hash = 'app_content';
    </script>
  {/if}
{/block}

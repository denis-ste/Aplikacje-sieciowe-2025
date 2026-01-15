<form method="post" action="{$app_root}/app/kredyt.php#app_content">
  <div class="row">
    <div class="col">
      <label>Kwota</label>
      <input type="text" name="kwota" value="{$kwota|default:''|escape}">
    </div>
    <div class="col">
      <label>Lata</label>
      <input type="text" name="lata" value="{$lata|default:''|escape}">
    </div>
    <div class="col">
      <label>Oprocentowanie (%)</label>
      <input type="text" name="oprocentowanie" value="{$oprocentowanie|default:''|escape}">
    </div>
  </div>

  <div style="margin-top:14px;">
    <button type="submit">Oblicz ratę</button>
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

{if isset($rata) && $rata !== null}
  <div class="result" style="margin-top:14px;">
    Miesięczna rata: <b>{$rata|string_format:"%.2f"} zł</b>
  </div>
{/if}

<div class="footer-actions">
  <a class="link" href="#app_top">Góra strony</a>
</div>

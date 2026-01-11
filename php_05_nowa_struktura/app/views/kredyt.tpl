{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>Kalkulator kredytowy</h1>

    <form method="post" action="{$app_url}/app/kredyt.php">
      <div class="form-row">
        <label>Kwota kredytu:</label>
        <input type="text" name="kwota" value="{$kwota|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Okres (w latach):</label>
        <input type="text" name="lata" value="{$lata|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Oprocentowanie roczne (%):</label>
        <input type="text" name="oprocentowanie" value="{$oprocentowanie|default:''|escape}">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary">Oblicz ratę</button>
      </div>
    </form>

    {if isset($messages) && $messages|@count > 0}
      <div class="msg msg-err">
        <ul>
          {foreach $messages as $m}
            <li>{$m|escape}</li>
          {/foreach}
        </ul>
      </div>
    {/if}

    {if isset($rata) && $rata !== null && (!isset($messages) || $messages|@count == 0)}
      <div class="result">Miesięczna rata: {$rata|string_format:"%.2f"} zł</div>
    {/if}

    <div class="backline">
      <a href="{$app_url}/app/home.php">Powrót</a>
    </div>
  </div>
{/block}

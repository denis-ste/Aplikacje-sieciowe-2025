{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>Zwykły kalkulator</h1>

    {if $restriction_info != ''}
      <div class="msg msg-warn">{$restriction_info|escape}</div>
    {/if}

    <form method="post" action="{$app_url}/app/calc.php">
      <div class="form-row">
        <label>Liczba 1:</label>
        <input type="text" name="liczba1" value="{$liczba1|escape}">
      </div>

      <div class="form-row">
        <label>Operacja:</label>
        <select name="operacja">
          <option value="plus" {if $operacja=='plus'}selected{/if}>+</option>
          <option value="minus" {if $operacja=='minus'}selected{/if}>-</option>
          <option value="times" {if $operacja=='times'}selected{/if}>*</option>
          <option value="div" {if $operacja=='div'}selected{/if}>\</option>
        </select>
      </div>

      <div class="form-row">
        <label>Liczba 2:</label>
        <input type="text" name="liczba2" value="{$liczba2|escape}">
      </div>

      <div class="form-row">
        <button type="submit" class="btn btn-primary">Oblicz</button>
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

    {if $wynik !== null && $messages|@count == 0}
      <div class="result">Wynik: {$wynik}</div>
    {/if}

    <div class="backline">
      <a href="{$app_url}/app/home.php">Powrót</a>
    </div>
  </div>
{/block}

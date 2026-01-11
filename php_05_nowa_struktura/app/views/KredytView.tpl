{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>Kalkulator kredytowy</h1>

    <form method="post" action="{$app_url}/?action=kredyt">
      <div class="form-row">
        <label>Kwota kredytu:</label>
        <input type="text" name="kwota" value="{$form->kwota|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Okres (w latach):</label>
        <input type="text" name="lata" value="{$form->lata|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Oprocentowanie (% rocznie):</label>
        <input type="text" name="oprocentowanie" value="{$form->oprocentowanie|default:''|escape}">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Oblicz ratę</button>
      </div>
    </form>

    {if $msgs->isError()}
      <div class="msg msg-error">
        <ul>
          {foreach $msgs->getErrors() as $m}
            <li>{$m|escape}</li>
          {/foreach}
        </ul>
      </div>
    {/if}

    {if $msgs->isInfo()}
      <div class="msg msg-ok">
        <ul>
          {foreach $msgs->getInfos() as $m}
            <li>{$m|escape}</li>
          {/foreach}
        </ul>
      </div>
    {/if}

    {if isset($res->rata) && !$msgs->isError()}
      <div class="result">Miesięczna rata: {$res->rata|string_format:"%.2f"} zł</div>
    {/if}

    <div class="backline">
      <a href="{$app_url}/?action=home">Powrót</a>
    </div>
  </div>
{/block}

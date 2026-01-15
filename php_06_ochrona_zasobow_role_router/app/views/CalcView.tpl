{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>Zwykły kalkulator</h1>

    <form method="post" action="{$app_url}/?action=calc">
      <div class="form-row">
        <label>Liczba 1:</label>
        <input type="text" name="liczba1" value="{$form->x|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Liczba 2:</label>
        <input type="text" name="liczba2" value="{$form->y|default:''|escape}">
      </div>

      <div class="form-row">
        <label>Operacja:</label>
        <select name="operacja">
          <option value="plus"  {if ($form->op|default:'plus')=='plus'}selected{/if}>+</option>
          <option value="minus" {if ($form->op|default:'plus')=='minus'}selected{/if}>-</option>
          <option value="times" {if ($form->op|default:'plus')=='times'}selected{/if}>*</option>
          <option value="div"   {if ($form->op|default:'plus')=='div'}selected{/if}>/</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Oblicz</button>
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

    {if isset($res->result) && !$msgs->isError()}
      <div class="result">
        Wynik: {$res->result}
      </div>
    {/if}

    <div class="backline">
      <a href="{$app_url}/?action=home">Powrót</a>
    </div>
  </div>
{/block}

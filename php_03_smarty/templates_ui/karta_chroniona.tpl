{extends file="layout.tpl"}

{block name=content}
  <div class="card wide">
    <h1>Karta chroniona</h1>

    {if !$is_admin}
      <div class="msg msg-warn">
        {foreach $messages as $m}
          {$m|escape}
        {/foreach}
      </div>
    {else}
      <div class="msg msg-ok">Masz dostęp (admin).</div>
    {/if}

    <div class="backline">
      <a href="{$app_url}/app/home.php">Powrót</a>
    </div>
  </div>
{/block}

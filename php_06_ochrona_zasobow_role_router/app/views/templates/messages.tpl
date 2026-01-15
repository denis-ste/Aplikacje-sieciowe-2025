{if $msgs && $msgs->isError()}
  <div class="msg msg-err">
    <ul>
      {foreach $msgs->getErrors() as $err}
        <li>{$err|escape}</li>
      {/foreach}
    </ul>
  </div>
{/if}

{if $msgs && $msgs->isInfo()}
  <div class="msg msg-ok">
    <ul>
      {foreach $msgs->getInfos() as $inf}
        <li>{$inf|escape}</li>
      {/foreach}
    </ul>
  </div>
{/if}

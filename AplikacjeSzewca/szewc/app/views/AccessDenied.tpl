{extends file="layouts/layout.tpl"}

{block name=content}

<h2 style="color:red;">⛔ Brak dostępu</h2>

<p>
    Nie masz uprawnień do wyświetlenia tej strony.
</p>

<br>

<a href="{$conf->action_url}{$backAction}">
    <button>Wróć do panelu</button>
</a>

{/block}
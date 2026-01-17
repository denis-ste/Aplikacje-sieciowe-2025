{extends file="layouts/layout.tpl"}

{block name=content}
<h2>Usługi (CRUD)</h2>

<p>
    <a class="btn-menu" href="{$conf->action_url}service_new">+ Dodaj usługę</a>
</p>

<form method="get" action="{$conf->action_url}admin_services" class="auth-form">
    <div class="form-group">
        <label for="q">Szukaj (nazwa)</label>
        <input id="q" type="text" name="q" value="{$q|default:''}">
    </div>
    <button type="submit" class="btn-submit">Szukaj</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Cena</th>
            <th>Aktywna</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        {foreach $services as $s}
            <tr>
                <td>{$s.id}</td>
                <td>{$s.name}</td>
                <td>{$s.price} zł</td>
                <td>{$s.is_active}</td>
                <td>
                    <a class="btn-menu" href="{$conf->action_url}service_edit?id={$s.id}">Edytuj</a>
                    <a class="btn-menu" href="{$conf->action_url}service_toggle?id={$s.id}">
                        {if $s.is_active == 'Y'}Wyłącz{else}Włącz{/if}
                    </a>
                </td>
            </tr>
        {/foreach}
        {if empty($services)}
            <tr><td colspan="5">Brak usług.</td></tr>
        {/if}
    </tbody>
</table>
{/block}

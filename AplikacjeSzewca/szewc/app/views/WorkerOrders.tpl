{extends file="layouts/layout.tpl"}

{block name=content}
<h2>Zlecenia (pracownik)</h2>

<form method="get" action="{$conf->action_url}worker_orders" class="auth-form">
    <div class="form-group">
        <label for="q">Szukaj (klient/usługa/produkt/opis)</label>
        <input id="q" type="text" name="q" value="{$q|default:''}">
    </div>

    <div class="form-group">
        <label for="status_id">Status</label>
        <select id="status_id" name="status_id">
            <option value="">(aktywne)</option>
            {foreach $statuses as $s}
                <option value="{$s.id}" {if $statusId == $s.id}selected{/if}>{$s.name}</option>
            {/foreach}
        </select>
    </div>

    <div class="form-group">
        <label>Termin odbioru (od - do)</label>
        <input type="date" name="date_from" value="{$dateFrom|default:''}">
        <input type="date" name="date_to" value="{$dateTo|default:''}">
    </div>

    <button type="submit" class="btn-submit">Filtruj</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Klient</th>
            <th>Typ</th>
            <th>Usługa / Produkty</th>
            <th>Termin</th>
            <th>Status</th>
            <th>Przypisany</th>
            <th>Opis</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        {foreach $orders as $o}
            <tr>
                <td>{$o.id}</td>
                <td>{$o.client_username}</td>
                <td>{if $o.order_type=='PRODUCT'}produkt{else}usługa{/if}</td>
                <td>
                    {if $o.order_type=='PRODUCT'}
                        {$itemsByOrder[$o.id]|default:'-'}
                    {else}
                        {$o.service_name}
                    {/if}
                </td>
                <td>
                    {if !empty($o.pickup_date)}
                        {$o.pickup_date}
                    {else}
                        <form method="post" action="{$conf->action_url}worker_pickup_set" style="display:flex; gap:10px; align-items:center;">
                            <input type="hidden" name="id" value="{$o.id}">
                            <input type="date" name="pickup_date" required style="padding:10px 12px; border-radius:14px; border:1px solid #e5e7eb;">
                            <button type="submit" class="btn-menu">Ustal</button>
                        </form>
                    {/if}
                </td>
                <td><span class="pill">{$o.status_name}</span></td>
                <td>{$o.worker_username|default:"-"}</td>
                <td>{$o.note|default:"-"}</td>
                <td>
                    {if $o.status_id == \core\OrderStatus::NEW}
                        <a class="btn-menu" href="{$conf->action_url}worker_status?id={$o.id}&to=ACCEPTED">Przyjmij</a>
                    {elseif $o.status_id == \core\OrderStatus::ACCEPTED}
                        <a class="btn-menu" href="{$conf->action_url}worker_status?id={$o.id}&to=READY">Gotowe</a>
                    {else}
                        -
                    {/if}
                </td>
            </tr>
        {/foreach}
        {if empty($orders)}
            <tr><td colspan="9">Brak zleceń.</td></tr>
        {/if}
    </tbody>
</table>
{/block}

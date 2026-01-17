{extends file="layouts/layout.tpl"}

{block name=content}
<h2>Moje zlecenia</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Typ</th>
            <th>Usługa / Produkty</th>
            <th>Termin odbioru</th>
            <th>Status</th>
            <th>Cena</th>
            <th>Pracownik</th>
            <th>Opis</th>
            <th>Akcja</th>
        </tr>
    </thead>
    <tbody>
        {foreach $orders as $o}
            <tr>
                <td>{$o.id}</td>
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
                        <span class="pill">nieustalony</span>
                    {/if}
                </td>
                <td><span class="pill">{$o.status_name}</span></td>
                <td>{$o.total_price} zł</td>
                <td>{$o.worker_username|default:"-"}</td>
                <td>{$o.note|default:"-"}</td>
                <td>
                    {if $o.status_code == 'READY'}
                        <a class="btn-menu" href="{$conf->action_url}order_pickup?id={$o.id}">Odbiór</a>
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

{extends file="layouts/layout.tpl"}

{block name=content}

<h2 class="center-title">Panel klienta</h2>

<h3 class="center-title">Usługi</h3>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Nazwa</th>
            <th>Cena</th>
            <th>Akcja</th>
        </tr>
    </thead>
    <tbody>
        {foreach $services as $s}
            <tr>
                <td>{$s.name}</td>
                <td>{$s.price} zł</td>
                <td>
                    <a class="btn-menu" href="{$conf->action_url}order_new?service_id={$s.id}">Złóż zlecenie</a>
                </td>
            </tr>
        {/foreach}
        {if empty($services)}
            <tr><td colspan="3">Brak usług.</td></tr>
        {/if}
    </tbody>
</table>
</div>

<h3 class="center-title">Produkty detaliczne</h3>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Nazwa</th>
            <th>Cena</th>
            <th>Stan</th>
            <th>Kup</th>
        </tr>
    </thead>
    <tbody>
        {foreach $products as $p}
            <tr>
                <td>{$p.name}</td>
                <td>{$p.price} zł</td>
                <td>{$p.stock_qty}</td>
                <td>
                    {if $p.stock_qty > 0}
                        <form method="post" action="{$conf->action_url}product_order_create" style="display:flex; gap:10px; align-items:center;">
                            <input type="hidden" name="product_id" value="{$p.id}">
                            <input type="number" name="qty" value="1" min="1" style="width:90px; padding:10px 12px; border-radius:14px; border:1px solid #e5e7eb;">
                            <button type="submit" class="btn-menu" style="margin-left:0;">Kup</button>
                        </form>
                    {else}
                        <span class="pill">Brak na stanie</span>
                    {/if}
                </td>
            </tr>
        {/foreach}
        {if empty($products)}
            <tr><td colspan="4">Brak produktów.</td></tr>
        {/if}
    </tbody>
</table>
</div>
{/block}

{extends file="layouts/layout.tpl"}

{block name=content}

<div class="page-center">
    <div class="page-card">
        <h2 class="center-title">Nowe zlecenie usługi</h2>

        <form method="post" action="{$conf->action_url}order_create" class="auth-form">

    <div class="form-group">
        <label for="service_id">Usługa</label>
        <select id="service_id" name="service_id" required>
            <option value="">-- wybierz --</option>
            {foreach $services as $s}
                <option value="{$s.id}" {if $serviceId == $s.id}selected{/if}>{$s.name} ({$s.price} zł)</option>
            {/foreach}
        </select>
    </div>

    <div class="form-group">
        <label for="note">Opis (opcjonalnie)</label>
        <textarea id="note" name="note" rows="4" placeholder="np. rozklejona podeszwa, kolor czarny"></textarea>
    </div>

            <button type="submit" class="btn-submit">Złóż zlecenie</button>

        </form>
    </div>
</div>
{/block}

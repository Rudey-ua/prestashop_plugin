<form method="post" action="{$action}">
    <select id="issuers" name="issuer" required>
        <option value="" selected disabled>{l s='Choose an issuer'}</option>
        {foreach from=$idealIssuers item=idealIssuer}
            <option value="{$idealIssuer.id}">{$idealIssuer.name}</option>
        {/foreach}
    </select>
</form>


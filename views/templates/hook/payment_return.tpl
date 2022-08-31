{extends file='page.tpl'}

{block name='content'}
    <h1>{l s='Your order at'}{$module}</h1>
    <h2>{l s='There was an error processing your order'}</h2>
    <h4 style="color: #7DA0B1">{l s='An error has occurred'}</h4>

    <p></p>
    <a href="/">{l s='Go to home page'}</a>
    <p></p>
{/block}
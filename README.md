# Integração com API PagCompleto

Funcionalidades da integração:

 - Processar pagamentos de lojas que utilizam a integração com o PagCompleto dos 
   pedidos pagos com cartão de crédito.

# Executando a integração:

Para executar a integração, no browser, digite o seguinte comando:

?operation=processOrderPayment&order=:order

onde ':order' será informado o código do pedido a ser processado

- exemplo de requisição: localhost/?operation=processOrderPayment&order=98302
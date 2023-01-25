# Integração com API PagCompleto

Informações do ambiente de desenvolvimento:

- Versão do PHP: 8.2.0
- Web Server: Apache

**Funcionalidades da integração:**

 - Processar pagamentos dos pedidos - das lojas que utilizam a integração com o PagCompleto -
   pagos com cartão de crédito.

# Executando a integração:

Para executar a integração, no browser, digite o seguinte comando:

?operation=processOrderPayment&order=:order

onde ':order' será informado o código do pedido a ser processado

- exemplo de requisição: localhost/?operation=processOrderPayment&order=98302
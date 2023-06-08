Desafio técnico - Dev PHP

O projeto consiste em um endpoint para buscar e listar os municípios de uma UF, usando como provedor a seguinte API: Brasil API, disponível em:  https://brasilapi.com.br/api/ibge/municipios/v1/{UF}.

No projeto temos a rota GET api/v1/municipios/{uf} disponivel para consultar os dados, nela é possível passar 2 parâmetros: page e perPage, por padrão os valores são 1 e 20 respectivamente, esses parâmetros são responsáveis por controlar a página da consulta e a quantidade de registros que será retornado.

No controlador buscamos os dados do provedor e montamos um array de retorno com o nome e o código do ibge, seguindo a estrutura:
{
‘name’: ‘Juiz de Fora,
‘ibge_code’:123
}
Para evitar requisições ao provider foi realizado um sistema de cache utilizando Redis, basicamente se não existir buscamos direto do provider e montamos a estrutura de retorno com todos os dados e salvamos o json no Redis com a chave municipio_{UF}, caso essa chave já tenha dados, apenas buscamos e é feito a paginação.
Foi utilizado o Redis por ser um armazenamento em memória e de rápido acesso e controle.

Para buscar os dados da API basta enviar um GET para o endpoint api/v1/municipios/{uf} onde, por exemplo:
curl --location --request GET 'http://127.0.0.1:8000/api/v1/municipios/mg?page=1&perPage=20'
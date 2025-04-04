# Guia para Subir a Aplicação no Ambiente Local

Siga os passos abaixo para configurar e executar a aplicação em seu ambiente local.

## Pré-requisitos

1. **Docker** e **Docker Compose** instalados no sistema.
2. Uma ferramenta para testar APIs, como **Postman** ou a extensão **Rest Client** do VS Code.

## Passos para Configuração

1. **Subir os containers da aplicação**  
   Execute o comando abaixo na raiz do projeto:
   ```bash
   docker compose up
   ```

2. **Verificar se a aplicação está rodando**  
   Abra o navegador e acesse a URL:  
   [http://localhost:8123/api/unidade](http://localhost:8123/api/unidade)

3. **Persistência do banco de dados**  
   Por padrão, ao subir a aplicação, o banco de dados é recriado em ambiente de desenvolvimento.  
   Para evitar a perda de dados armazenados:
   - Abra o arquivo `./app/docker-entrypoint.sh`.
   - Comente todas as linhas, exceto a última, utilizando `#`.

## Testando os Endpoints da API

- Utilize o **Postman** ou a extensão **Rest Client** para testar as chamadas aos endpoints da API.
- Um arquivo de exemplo com as requisições está disponível em:  
  [`api-requests.http`](./api-requests.http)
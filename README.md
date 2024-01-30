# Seguir os seguintes passos para subir a aplicação no ambiente local

01 - Instalar o Docker.

02 - Executar o comando "sudo docker compose up --build". Se der erro ao baixar o pacote "php:apache", execute o comando "docker pull php:apache".

03 - Abrir outra aba no terminal e entrar no container em execução com o comando "docker exec -it dadm-modulo-mensagem bash".

04 - Executar os seguintes comandos:
./instalar_dependencias.sh
./criar_carregar_db.sh

05 - Abrir a seguinte url no navegador: "localhost:8123/api/unidade".

06 - Instalar o Postman ou similar no computador para testar as chamadas aos endpoints da API.

------------------------
# Documentação da API

# GET
http://localhost:8123/api/mensagem/siglaOM
http://localhost:8123/api/mensagem/detalhes/idMensagem

# POST
http://localhost:8123/api/mensagem

Exemplo JSON: <br />
{
    "assunto": "Assunto",
    "texto": "Texto",
    "sigilo": "Ostensivo",
    "prazo": "20240131",
    "observacao": "Observação",
    "unidadeOrigemSigla": "SGM",
    "unidadesDestinoSiglas" : ["DAdM","DFM"],
    "unidadesInformacaoSiglas" : ["DGOM"]
}

# PUT
http://localhost:8123/api/mensagem/idMensagem

Exemplo JSON: <br />
{
    "assunto": "Assunto",
    "texto": "Texto",
    "sigilo": "Ostensivo",
    "prazo": "20240131",
    "observacao": "Observação",
    "unidadeOrigemSigla": "SGM",
    "unidadesDestinoSiglas" : ["DAdM","DFM"],
    "unidadesInformacaoSiglas" : ["DGOM"]
}

# DELETE
http://localhost:8123/api/mensagem/idMensagem

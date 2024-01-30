# Seguir os seguintes passos para subir a aplicação no ambiente local

01 - Instalar o Docker.

02 - Executar o comando "sudo docker compose up --build". Se der erro ao baixar o pacote "php:apache", execute o comando "docker pull php:apache".

03 - Abrir a seguinte url no navegador: "localhost:8123/api/unidade".

04 - Instalar o Postman ou similar no computador para testar as chamadas aos endpoints da API.

------------------------
# Documentação da API

# GET
http://localhost:8123/api/mensagem/siglaOM
<br />
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

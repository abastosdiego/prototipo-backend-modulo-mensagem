# Seguir os seguintes passos para subir a aplicação no ambiente local

01 - Instalar o Docker.

02 - Renovear o arquivo apt.conf-example para apt.conf

03 - Editar o conteúdo do arquivo apt.conf, informando o nip e senha de internet.

04 - Executar o comando "sudo docker compose up". Se der erro ao baixar o pacote "php:apache", executar o comando "docker pull php:apache".

05 - Abrir a seguinte url no navegador: "localhost:8123".

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
    "unidadesDestinoSiglas" : ["DAdM"],
    "unidadesInformacaoSiglas" : ["DFM"]
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
    "unidadesDestinoSiglas" : ["DAdM"],
    "unidadesInformacaoSiglas" : ["DFM"]
}

# DELETE
http://localhost:8123/api/mensagem/idMensagem

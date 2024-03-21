# Seguir os seguintes passos para subir a aplicação no ambiente local

01 - Instalar o Docker.

02 - Execute o comando "docker pull php:apache"

03 - Executar o comando "sudo -E docker compose up".

04 - Abrir a seguinte url no navegador: "localhost:8123/api/unidade".

05 - Desta forma que foi configurado, ao subir a aplicação o sistema apaga o banco de dados e cria novamente em ambiente de desenvolvimento. Caso não queira perder os dados armazenados, abrir o arquivo "./app/docker-entrypoint.sh" e comentar as linhas, exceto a última, utilizando "#".

06 - Instalar o Postman ou similar no computador para testar as chamadas aos endpoints da API.

------------------------
# Documentação da API

# Login

# POST
http://localhost:8123/api/login_check
<br />
{
    "username": "nip",
    "password": "dadm123"
}

# Todas as requisições em diantes precisarão passar no header o token recebido na api de login
Authorization: Bearer {token}

# Unidade

# GET
http://localhost:8123/api/unidade
<br />
<br />

# Usuario

# GET
http://localhost:8123/api/usuario/unidade/{siglaUnidade}
<br />
<br />

# Mensagem
# GET
http://localhost:8123/api/mensagem/rascunho
<br />
<br />
# GET
http://localhost:8123/api/mensagem/aguardando-transmissao
<br />
<br />
# GET
http://localhost:8123/api/mensagem/enviadas
<br />
<br />
# GET
http://localhost:8123/api/mensagem/{idMensagem}
<br />
<br />

# POST
http://localhost:8123/api/mensagem

<br />
{
    "assunto": "Assunto",
    "texto": "Texto",
    "sigilo": "Ostensivo",
    "prazo_transmissao": "20240131",
    "observacao": "Observação",
    "unidadesDestinoSiglas" : ["DAdM","DFM"],
    "unidadesInformacaoSiglas" : ["DGOM"],
    "exige_resposta" : true,
    "prazo_resposta" : "20240501"
}

# PUT
http://localhost:8123/api/mensagem/{idMensagem}

<br />
{
    "assunto": "Assunto",
    "texto": "Texto",
    "sigilo": "Ostensivo",
    "prazo_transmissao": "20240131",
    "observacao": "Observação",
    "unidadesDestinoSiglas" : ["DAdM","DFM"],
    "unidadesInformacaoSiglas" : ["DGOM"],
    "exige_resposta" : true,
    "prazo_resposta" : "20240501"
}

# DELETE
http://localhost:8123/api/mensagem/{idMensagem}

<br />

# PUT
http://localhost:8123/api/mensagem/autorizar/{idMensagem}

<br />

# Comentários

# GET
(Via API de mensagem)

# POST
http://localhost:8123/api/mensagem/comentario/{idMensagem}

<br />
{
    "texto": "texto do comentário"
}

# PUT
http://localhost:8123/api/mensagem/comentario/{idMensagem}/{idComentario}

<br />
{
    "texto": "texto do comentário"
}

# DELETE
http://localhost:8123/api/mensagem/comentario/{idMensagem}/{idComentario}


# Trâmites

# POST
http://localhost:8123/api/mensagem/tramite/{idMensagem}

<br />
{
    "tramite_futuro": ["01052331","87300061","85988359"]
}

# PUT
http://localhost:8123/api/mensagem/tramite/alterar/{idMensagem}

<br />
{
    "tramite_futuro": ["01052331","17090148","87300061","85988359"]
}

# PUT
http://localhost:8123/api/mensagem/tramite/encaminhar/{idMensagem}


# PUT
http://localhost:8123/api/mensagem/tramite/encaminhar-para/{idMensagem}

<br />
{
    "usuario": "05060825043"
}

# Desafio Técnico - Tray #

Este teste tem como objetivo avaliar suas habilidades no desenvolvimento de
aplicações Full Stack, ou seja, na criação de uma aplicação separada em Back-End
e Front-End. Além disso, buscamos entender seu conhecimento em integrações
com APIs externas, com foco específico no Google.
O desafio foi projetado para ser simples, mas suficiente para demonstrar sua
capacidade de interpretar documentações, escrever código limpo, padronizado e
escalável.

## Guia de Instalação e Configuração

### Pré-requisitos
- Docker
- Docker Compose
- Ngrok

## Clonando o Projeto
```sh
git clone git@github.com:jbohme/Tray-Test.git
cd Tray-Test/
```
Execute o script de preparação do ambiente: ``./setup.sh``

Caso seja necessário realizar na mão, o passo a passo do script é:

### 1 - Configuração do Docker
```sh
docker-compose build
docker-compose up -d
```

### 2 - Configuração do Backend
```sh
docker exec -it backend_app composer install
docker exec -it backend_app cp .env.example .env
docker exec -it backend_app php artisan key:generate
docker exec -it backend_app php artisan jwt:secret
```

### 3 - Executando as Migrações
Este processo pode demorar, pois serão gerados **150 mil usuários fake**.
```sh
docker exec -it backend_app php artisan migrate --seed
```

### 4 - Configuração do Frontend
```sh
docker exec -itu root frontend_app npm install
docker exec -itu root frontend_app npm run dev
```

## Configuração do OAuth no Google Console
Para utilizar o OAuth, utilizamos o **ngrok** para criar um túnel do ambiente local para a internet.

### Instalação e Configuração do Ngrok
Siga a documentação oficial para instalação e configuração:
[Ngrok para Linux](https://ngrok.com/downloads/linux)

Após a instalação, execute:
```sh
ngrok http 80
```
Copie a **URL gerada**, pois ela será necessária no Google Console.

### Criando Credenciais no Google Console
1. Acesse **APIs e Serviços > Credenciais**.
2. Clique no botão **Criar credenciais** > **ID do cliente OAuth**.
3. Selecione o Tipo do Aplicativo como **Aplicativo Web**.
4. Defina um nome para a credencial.
5. Adicione o **URI de redirecionamento autorizado** seguindo este padrão:
   ```
   <URL_GERADA_NGROK>/api/auth/google/callback
   ```
   **⚠ Atenção:** Toda vez que reiniciar o **ngrok**, uma nova URL será gerada. Portanto, será necessário atualizar essa URL no Google Console.
6. Após configurar, clique em **Criar**.
7. No modal exibido, copie **ID do cliente** e **Chave secreta do cliente**.

### Atualizando as Credenciais no Backend Laravel
Edite o arquivo **.env** no backend e adicione as credenciais obtidas
```ini
GOOGLE_CLIENT_ID=SEU_ID_DO_CLIENTE
GOOGLE_CLIENT_SECRET=SUA_CHAVE_SECRETA
GOOGLE_REDIRECT_URI=<URL_GERADA_NGROK>/api/auth/google/callback
```

Após a atualização, execute:
```sh
docker exec -ti backend_app php artisan optimize
```

## Finalizando
Agora a aplicação está pronta! Acesse o frontend pelo navegador em:
```sh
http://localhost:5173
```


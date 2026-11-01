# 🏢 RH Management

Sistema de gerenciamento de Recursos Humanos desenvolvido com **Laravel**, utilizando autenticação com **Laravel Fortify**, banco de dados MySQL e ambiente Docker com Laravel Sail.

## 🛠️ Tecnologias utilizadas

- **Laravel** — Framework PHP.
- **Laravel Fortify** — Autenticação de usuários.
- **Laravel Sail** — Ambiente de desenvolvimento com Docker.
- **MySQL** — Banco de dados relacional.
- **Mailpit** — Testes de envio e recebimento de e-mails.
- **Git e GitHub** — Controle de versão.

---

## 📧 Configuração de e-mail — Mailpit

Neste projeto, utilizamos o **Mailpit** no Ubuntu para simular o envio e recebimento de e-mails durante o desenvolvimento.

As configurações podem ser consultadas nos seguintes arquivos:

| Arquivo | Descrição |
|---|---|
| `.env` | Configurações de conexão com o servidor de e-mail. |
| `compose.yaml` | Configuração do serviço Mailpit no Docker. |
| `routes/web.php` | Rota utilizada para testar o envio de e-mails. |

> **Nota:** O Mailpit permite testar o envio de e-mails sem encaminhá-los para destinatários reais.

---

## 🔐 Instalação do Laravel Fortify

O Laravel Fortify fornece os recursos de autenticação utilizados no sistema.

### 1. Instalar o Fortify

```bash
composer require laravel/fortify
```

### 2. Publicar os arquivos de configuração

```bash
php artisan fortify:install
```

### 3. Criar a migration da tabela de cache

```bash
php artisan make:cache-table
```

> Execute esse comando somente se a migration da tabela de cache ainda não existir.

### 4. Executar migrations e seeders

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

**Atenção:** o comando `migrate:fresh` apaga todas as tabelas do banco de dados antes de recriá-las.

---

## 🏭 Factories e Migrations

### Criar a factory UserDetailFactory

```bash
php artisan make:factory UserDetailFactory --model=UserDetail
```

Essa factory será utilizada para gerar dados de teste relacionados ao modelo `UserDetail`.

### Adicionar remember_token à tabela users

```bash
php artisan make:migration add_remember_token_to_users_table --table=users
```

Essa migration permite adicionar o campo `remember_token` à tabela `users`, utilizado pelo Laravel no recurso de lembrar a autenticação do usuário.

---

# 🌿 Controle de versão — Git

O projeto utiliza duas branches locais com finalidades diferentes.

| Branch | Finalidade |
|---|---|
| `main` | Desenvolvimento contínuo, contendo todos os commits. |
| `master` | Versões organizadas, contendo apenas commits de funcionalidades finalizadas. |

## 1. Remover o repositório remoto antigo
Verifique o remoto atual:

```bash
git remote -v
```

Se aparecer algo como:
```bash
origin  https://github.com/usuario/repositorio-antigo.git (fetch)
origin  https://github.com/usuario/repositorio-antigo.git (push)
```

Remova a configuração:
```bash
git remote remove origin
```

## 2. Apagar uma branch (master)
Verifique as branches no seu git:

```bash
git branch
```
Vai aparecer algo como:
```bash
* main
  master
```
Para remover a branch `master`, que não está ativa, digite o comando:

```bash
git branch -D master
```

## 3. Criar uma branch sem histórico anterior

```bash
git switch --orphan master
```

Esse comando cria a branch `master` com um histórico independente da branch `main`.

## 4. Copiar os arquivos da main

```bash
git restore --source=main --staged --worktree .
```

Esse comando copia os arquivos da última versão commitada na `main` para a `master`, sem copiar o histórico de commits.

## 5. Criar o primeiro commit

```bash
git add .
git commit -m "Finalizado sistema de login"
```

A branch `master` terá seu primeiro commit contendo os arquivos do projeto.

## 6. Voltar para a branch de desenvolvimento

```bash
git switch main
```

Na branch `main`, o desenvolvimento continuará normalmente, permitindo realizar quantos commits forem necessários.

## 7. Atualizar a branch master

Quando uma funcionalidade estiver finalizada, primeiro registre todas as alterações desejadas na `main`.

Depois, execute:

```bash
git switch master
```

Copie os arquivos atualizados:

```bash
git restore --source=main --staged --worktree .
```

Crie um commit representando a funcionalidade concluída:

```bash
git commit -m "Finalizada página de perfil"
```

## 8. Enviar as alterações para o GitHub

Considerando que o remoto `origin` já esteja configurado e que a branch de publicação no GitHub seja `main`:

```bash
git push origin master:main
```

Após a publicação, volte para a branch de desenvolvimento:

```bash
git switch main
```

---

## 📊 Exemplo do histórico de commits

### Branch main — Desenvolvimento

```text
* Corrigindo validação do perfil
* Implementando atualização de dados
* Criando formulário de perfil
* Ajustando recuperação de senha
* Implementando recuperação de senha
* Criando autenticação com Fortify
```

### Branch master — Publicação

```text
* Finalizada página de perfil
* Finalizado sistema de login
```

Dessa forma, mantemos um histórico detalhado de desenvolvimento e um histórico simplificado das funcionalidades concluídas.

---

## ⚠️ Boas práticas

- Realizar os commits de desenvolvimento na branch `main`.
- Utilizar a branch `master` somente para funcionalidades concluídas.
- Verificar o `git status` antes de alternar entre branches.
- Não versionar arquivos sensíveis, como `.env`.
- Manter o `.gitignore` atualizado.
- Não executar `migrate:fresh` em produção sem avaliar a perda de dados.
- Evitar merges entre `main` e `master`, pois possuem históricos independentes.

---

**RH Management** — Projeto desenvolvido para estudos e aplicação prática dos recursos do Laravel.

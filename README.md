# Sistema de Barbearia

Aplicação web para gerenciamento de agendamentos, clientes e barbeiros, desenvolvida como projeto de portfólio.

## Status do projeto

🚧 Em desenvolvimento.

## Tecnologias

- PHP 8+
- MySQL 8
- PDO (prepared statements)
- HTML5, CSS3, JavaScript
- Arquitetura MVC sem framework

## Funcionalidades planejadas

- Cadastro e login com roles: admin, barbeiro e cliente
- Agendamento online com verificação de disponibilidade de horário
- Painel do barbeiro com agenda do dia
- Painel administrativo com listagem e status dos agendamentos
- Histórico de atendimentos por cliente
- Cadastro de serviços com preço e duração
- Interface responsiva

## Como rodar localmente

1. Clone o repositório dentro da pasta `htdocs` do XAMPP
2. Inicie o Apache e o MySQL no XAMPP Control Panel
3. Crie o banco `barbearia` no phpMyAdmin e execute o script em `database/schema.sql`
4. Copie `config/database.example.php` para `config/database.php` e ajuste as credenciais se necessário
5. Acesse `http://localhost/barbearia/`

## Estrutura do projeto

```
barbearia/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
├── config/
├── core/
└── public/
```
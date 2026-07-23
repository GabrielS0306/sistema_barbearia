# Sistema de Barbearia

Aplicação web completa para gerenciamento de agendamentos, clientes e barbeiros, desenvolvida como projeto de portfólio com foco em backend PHP.

## 🔗 Demo

Acesse o sistema em produção: [barb-system.rf.gd/barbearia/login](https://barb-system.rf.gd/barbearia/login)

> ⚠️ Ambiente de demonstração. Para testar, crie uma conta na tela de registro ou entre em contato.

## 📸 Screenshots

### Login
![Login](database/screenshots/login.png)

### Painel Administrativo
![Dashboard](database/screenshots/dashboard.png)

### Agendar Horário
![Agendamento](database/screenshots/agendamento.png)

### Painel do Barbeiro
![Barbeiro](database/screenshots/barbeiro.png)

## 🚀 Tecnologias

- **Back-end:** PHP 8+
- **Banco de dados:** MySQL 8
- **Acesso ao banco:** PDO com prepared statements
- **Front-end:** HTML5, CSS3, JavaScript (ES6+)
- **CSS Framework:** Tailwind CSS (via CDN)
- **Geração de PDF:** DOMPDF
- **E-mail:** PHPMailer + Gmail SMTP
- **Gerenciador de dependências:** Composer
- **Arquitetura:** MVC sem framework
- **Versionamento:** Git/GitHub com Conventional Commits e branches por feature

## ⚙️ Funcionalidades

### Admin
- Dashboard com métricas (clientes, barbeiros, agendamentos do dia, receita do mês)
- CRUD de serviços e barbeiros com upload de foto
- Soft delete de barbeiros com reativação
- Painel de agendamentos com filtros por data, barbeiro e status
- Confirmação de reembolso para agendamentos cancelados
- Geração de relatório de agendamentos por período em PDF

### Barbeiro
- Agenda do dia filtrável por data
- Atualização de status dos agendamentos (pendente, confirmado, concluído, cancelado)
- Exportação da agenda do dia em PDF

### Cliente
- Cadastro e login com hash de senha
- Agendamento online com verificação de disponibilidade em tempo real (AJAX)
- Seleção de forma de pagamento (dinheiro, PIX, cartão)
- Cancelamento de agendamento com solicitação de reembolso automática
- Adiamento de agendamento para nova data/hora
- Download de comprovante de agendamento em PDF
- Listagem de agendamentos com status de pagamento e paginação

### Sistema
- Arquitetura MVC sem framework
- Roteador customizado com middleware de autenticação por roles
- Proteção CSRF em todos os formulários POST
- Validações no frontend (JavaScript) e no backend (PHP)
- API REST com endpoints para barbeiros, serviços, horários e agendamentos
- AJAX consumindo a API para horários disponíveis em tempo real
- Paginação nas listagens
- E-mail de confirmação e cancelamento via PHPMailer
- Interface responsiva com menu hambúrguer no mobile
- Geração de PDFs com DOMPDF

## 🗄️ Banco de Dados

- Script SQL: [`database/schema.sql`](database/schema.sql)
- Diagrama: [`database/diagrama.png`](database/diagrama.png)

## 🔧 Como rodar localmente

1. Clone o repositório dentro da pasta `htdocs` do XAMPP:
```bash
git clone https://github.com/GabrielS0306/sistema_barbearia.git barbearia
```

2. Inicie o **Apache** e o **MySQL** no XAMPP Control Panel

3. Instale as dependências via Composer:
```bash
cd barbearia
C:\xampp\php\php.exe composer.phar install
```

4. Crie o banco de dados no phpMyAdmin:
   - Nome: `barbearia`
   - Execute o script: `database/schema.sql`

5. Copie os arquivos de configuração:
```bash
cp config/database.example.php config/database.php
cp config/mail.example.php config/mail.php
```

6. Ajuste as credenciais em `config/database.php` e `config/mail.php`

7. Acesse: `http://localhost/barbearia/login`

## 🌐 API REST

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/barbeiros` | Lista barbeiros ativos |
| GET | `/api/servicos` | Lista serviços |
| GET | `/api/horarios?barbeiro_id=X&data=Y` | Horários disponíveis |
| GET | `/api/agendamentos` | Agendamentos do cliente logado |
| POST | `/api/agendamentos` | Criar novo agendamento |

## 📁 Estrutura do projeto

```
barbearia/
├── app/
│ ├── controllers/
│ ├── models/
│ └── views/
├── config/
├── core/
├── database/
├── public/
│ ├── assets/js/
│ └── uploads/
└── vendor/
```

## 🔮 Próximas funcionalidades

- Perfil do cliente (atualizar nome e telefone)
- Integração PIX via Mercado Pago
- Lembrete automático por e-mail 24h antes do agendamento
- Autenticação JWT para a API REST
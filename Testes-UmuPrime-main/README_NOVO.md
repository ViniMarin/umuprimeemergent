# 🏠 Sistema de Gestão Imobiliária - UmuPrime

Sistema completo de gestão imobiliária desenvolvido em Laravel 10, com painel administrativo robusto e interface pública responsiva.

## 📋 Sumário

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Funcionalidades](#funcionalidades)
- [Requisitos do Sistema](#requisitos-do-sistema)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Uso](#uso)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Contribuindo](#contribuindo)
- [Licença](#licença)

---

## 📖 Sobre o Projeto

O **Sistema de Gestão Imobiliária UmuPrime** é uma plataforma completa para gerenciamento de imóveis, desenvolvida para atender as necessidades de imobiliárias modernas. O sistema oferece:

- **Interface Pública**: Vitrine de imóveis com sistema de filtros avançado
- **Painel Administrativo**: CRUD completo com controle de usuários e permissões
- **Gestão de Imagens**: Upload múltiplo com ordenação
- **Sistema de Características**: Amenidades customizáveis por imóvel
- **Filtros Avançados**: Busca por tipo, valor, localização e muito mais

---

## 🚀 Tecnologias Utilizadas

### Backend
- **Laravel 10.x** - Framework PHP
- **PHP 8.1+** - Linguagem de programação
- **MySQL/MariaDB** - Banco de dados relacional

### Frontend
- **Blade Templates** - Engine de templates do Laravel
- **Bootstrap 5** - Framework CSS
- **Vite** - Build tool moderno
- **SASS** - Pré-processador CSS

### Bibliotecas Principais
- **Laravel Sanctum** - Autenticação API
- **Laravel UI** - Scaffolding de autenticação
- **Intervention Image** (opcional) - Manipulação de imagens

---

## ✨ Funcionalidades

### Área Pública
- ✅ Listagem de imóveis disponíveis
- ✅ Filtros por tipo de negócio (aluguel/venda)
- ✅ Filtros por tipo de imóvel, cidade, bairro e faixa de preço
- ✅ Busca por referência
- ✅ Página de detalhes com galeria de imagens
- ✅ Imóveis relacionados
- ✅ Páginas institucionais (Sobre, Contato)
- ✅ Design responsivo

### Painel Administrativo
- ✅ Dashboard com estatísticas em tempo real
- ✅ CRUD completo de imóveis
- ✅ Upload múltiplo de imagens com ordenação
- ✅ Gestão de características/amenidades
- ✅ Sistema de destaques
- ✅ Controle de status (disponível, vendido, alugado)
- ✅ CRUD de usuários (apenas admins)
- ✅ Controle de permissões (admin/usuário comum)
- ✅ Configuração de banner da home
- ✅ Filtros e busca avançada

### Segurança
- ✅ Autenticação robusta
- ✅ Proteções contra remoção do último admin
- ✅ Proteções contra auto-remoção de privilégios
- ✅ Validações completas em todos os formulários
- ✅ Hash de senhas com bcrypt
- ✅ CSRF protection

---

## 💻 Requisitos do Sistema

### Obrigatórios
- PHP >= 8.1
- Composer >= 2.0
- Node.js >= 16.x
- NPM ou Yarn
- MySQL >= 5.7 ou MariaDB >= 10.3
- Servidor web (Apache/Nginx)

### Extensões PHP Necessárias
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD ou Imagick (para manipulação de imagens)

---

## 📦 Instalação

### 1. Clone o Repositório

```bash
git clone https://github.com/seu-usuario/umuprime.git
cd umuprime
```

### 2. Instale as Dependências do PHP

```bash
composer install
```

### 3. Instale as Dependências do Node.js

```bash
npm install
# ou
yarn install
```

### 4. Configure o Ambiente

Copie o arquivo de exemplo e configure as variáveis:

```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações:

```env
APP_NAME="UmuPrime Imóveis"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umuprime
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### 5. Gere a Chave da Aplicação

```bash
php artisan key:generate
```

### 6. Execute as Migrations

```bash
php artisan migrate
```

### 7. Crie o Link Simbólico para Storage

```bash
php artisan storage:link
```

### 8. (Opcional) Popule o Banco com Dados de Teste

```bash
php artisan db:seed
```

### 9. Compile os Assets

Para desenvolvimento:
```bash
npm run dev
```

Para produção:
```bash
npm run build
```

### 10. Inicie o Servidor de Desenvolvimento

```bash
php artisan serve
```

Acesse: `http://localhost:8000`

---

## ⚙️ Configuração

### Criando o Primeiro Usuário Administrador

Você pode criar o primeiro admin via tinker:

```bash
php artisan tinker
```

Depois execute:

```php
$user = new App\Models\User();
$user->name = 'Administrador';
$user->email = 'admin@umuprime.com';
$user->password = bcrypt('senha123');
$user->is_admin = true;
$user->save();
```

### Configuração de Email (Opcional)

Para funcionalidades de email, configure no `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Configuração de Storage

As imagens são armazenadas em `storage/app/public/`. Certifique-se de que:

1. O link simbólico foi criado: `php artisan storage:link`
2. A pasta tem permissões adequadas: `chmod -R 775 storage bootstrap/cache`

---

## 🎯 Uso

### Acesso ao Painel Administrativo

1. Acesse: `http://localhost:8000/login`
2. Use as credenciais do administrador criado
3. Você será redirecionado para `/admin`

### Gerenciando Imóveis

#### Criar Novo Imóvel
1. Painel Admin → Imóveis → Novo Imóvel
2. Preencha os dados obrigatórios
3. Faça upload das imagens (múltiplas)
4. Adicione características separadas por vírgula
5. Salve

#### Editar Imóvel
1. Painel Admin → Imóveis → Editar
2. Modifique os campos desejados
3. Adicione/remova imagens
4. Atualize características
5. Salve

### Gerenciando Usuários (Apenas Admins)

1. Painel Admin → Usuários
2. Crie, edite ou remova usuários
3. Defina permissões de administrador

**Nota**: O sistema impede:
- Remover o último administrador
- Auto-remoção de privilégios de admin
- Auto-exclusão de conta

### Configurando o Banner da Home

1. Painel Admin → Configurações → Home
2. Faça upload da imagem (recomendado: 1920x756px)
3. Salve

---

## 📁 Estrutura do Projeto

```
umuprime/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── HomeController.php
│   │       ├── ImovelController.php
│   │       └── Admin/
│   │           ├── AdminController.php
│   │           ├── ImovelAdminController.php
│   │           ├── UserController.php
│   │           └── SiteSettingsController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Imovel.php
│   │   ├── ImagemImovel.php
│   │   ├── CaracteristicaImovel.php
│   │   └── SiteSetting.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── images/
│   └── storage/ (link simbólico)
├── resources/
│   ├── views/
│   │   ├── home.blade.php
│   │   ├── layouts/
│   │   ├── admin/
│   │   ├── imovel/
│   │   └── auth/
│   ├── css/
│   ├── js/
│   └── sass/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   └── app/
│       └── public/
│           ├── imoveis/
│           └── banners/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 🗄️ Estrutura do Banco de Dados

### Tabelas Principais

#### `users`
- Usuários do sistema (admin e comum)

#### `imoveis`
- Dados principais dos imóveis

#### `imagens_imoveis`
- Imagens vinculadas aos imóveis

#### `caracteristicas_imoveis`
- Características/amenidades dos imóveis

#### `site_settings`
- Configurações gerais do site (singleton)

---

## 🔒 Segurança

### Boas Práticas Implementadas

1. **Senhas**: Hash com bcrypt, mínimo 8 caracteres com letras e números
2. **CSRF**: Proteção em todos os formulários
3. **Validações**: Validação server-side em todas as entradas
4. **Permissões**: Sistema de gates para controle de acesso
5. **SQL Injection**: Uso de Eloquent ORM e prepared statements
6. **XSS**: Escape automático no Blade
7. **File Upload**: Validação de tipos e tamanhos
8. **Transações**: Uso de DB transactions para operações críticas

---

## 🚀 Deploy em Produção

### Checklist de Deploy

- [ ] Configure `APP_ENV=production` no `.env`
- [ ] Desative `APP_DEBUG=false`
- [ ] Configure URL correta em `APP_URL`
- [ ] Configure banco de dados de produção
- [ ] Execute: `composer install --optimize-autoloader --no-dev`
- [ ] Execute: `php artisan config:cache`
- [ ] Execute: `php artisan route:cache`
- [ ] Execute: `php artisan view:cache`
- [ ] Execute: `npm run build`
- [ ] Configure permissões: `chmod -R 775 storage bootstrap/cache`
- [ ] Configure HTTPS
- [ ] Configure backups automáticos

### Servidor Recomendado (Nginx)

```nginx
server {
    listen 80;
    server_name seudominio.com;
    root /var/www/umuprime/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Para contribuir:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Padrões de Código

- Siga PSR-12 para PHP
- Use nomenclatura descritiva em português para variáveis de negócio
- Documente funções complexas com PHPDoc
- Mantenha controllers enxutos, use Services quando necessário
- Escreva testes para novas funcionalidades

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 👥 Autores

- **Equipe UmuPrime** - Desenvolvimento inicial

---

## 📞 Suporte

Para suporte, entre em contato:
- Email: contato@umuprime.com
- Website: https://www.umuprime.com

---

## 🙏 Agradecimentos

- Laravel Framework
- Bootstrap Team
- Comunidade PHP
- Todos os contribuidores

---

**Desenvolvido com ❤️ para imobiliárias modernas**

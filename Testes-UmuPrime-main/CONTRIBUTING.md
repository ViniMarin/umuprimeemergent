# 🤝 Guia de Contribuição

Obrigado por considerar contribuir com o Sistema de Gestão Imobiliária UmuPrime! Este documento fornece diretrizes para contribuir com o projeto.

## 📋 Índice

- [Código de Conduta](#código-de-conduta)
- [Como Contribuir](#como-contribuir)
- [Padrões de Código](#padrões-de-código)
- [Padrões de Commit](#padrões-de-commit)
- [Processo de Pull Request](#processo-de-pull-request)
- [Reportando Bugs](#reportando-bugs)
- [Sugerindo Melhorias](#sugerindo-melhorias)

---

## 📜 Código de Conduta

Este projeto adota um código de conduta. Ao participar, você concorda em manter um ambiente respeitoso e inclusivo.

---

## 🚀 Como Contribuir

### 1. Fork o Repositório

Clique no botão "Fork" no GitHub para criar sua própria cópia.

### 2. Clone seu Fork

```bash
git clone https://github.com/seu-usuario/umuprime.git
cd umuprime
```

### 3. Configure o Upstream

```bash
git remote add upstream https://github.com/original/umuprime.git
```

### 4. Crie uma Branch

```bash
git checkout -b feature/nome-da-feature
```

Tipos de branches:
- `feature/` - Nova funcionalidade
- `bugfix/` - Correção de bug
- `hotfix/` - Correção urgente
- `refactor/` - Refatoração de código
- `docs/` - Apenas documentação

### 5. Faça suas Alterações

Desenvolva sua funcionalidade ou correção seguindo os padrões do projeto.

### 6. Commit suas Mudanças

```bash
git add .
git commit -m "feat: adiciona filtro por data de cadastro"
```

### 7. Push para seu Fork

```bash
git push origin feature/nome-da-feature
```

### 8. Abra um Pull Request

Vá até o repositório original e clique em "New Pull Request".

---

## 💻 Padrões de Código

### PHP (PSR-12)

#### Estrutura de Classes

```php
<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Http\Request;

/**
 * Controller para gerenciar imóveis
 * 
 * Descrição detalhada se necessário
 */
class ImovelController extends Controller
{
    /**
     * Exibe lista de imóveis
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Código aqui
    }
}
```

#### Nomenclatura

- **Classes**: PascalCase (`ImovelController`, `User`)
- **Métodos**: camelCase (`getImoveis()`, `storeImagem()`)
- **Variáveis**: camelCase (`$imovelAtual`, `$totalImoveis`)
- **Constantes**: UPPER_SNAKE_CASE (`MAX_UPLOAD_SIZE`)
- **Namespaces**: Seguir estrutura PSR-4

#### Boas Práticas PHP

```php
// ✅ BOM
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'titulo' => 'required|string|max:255',
    ]);

    $imovel = Imovel::create($validated);

    return redirect()
        ->route('admin.imoveis.index')
        ->with('success', 'Imóvel cadastrado com sucesso!');
}

// ❌ EVITAR
public function store(Request $request)
{
    $imovel = new Imovel;
    $imovel->titulo = $request->titulo;
    $imovel->save();
    
    return redirect('admin/imoveis');
}
```

### Blade Templates

#### Estrutura

```blade
@extends('layouts.app')

@section('title', 'Título da Página')

@section('content')
    <div class="container">
        <h1>{{ $titulo }}</h1>
        
        @if ($imoveis->count() > 0)
            @foreach ($imoveis as $imovel)
                <div class="imovel-card">
                    <h2>{{ $imovel->titulo }}</h2>
                    <p>{{ $imovel->descricao }}</p>
                </div>
            @endforeach
        @else
            <p>Nenhum imóvel encontrado.</p>
        @endif
    </div>
@endsection
```

#### Boas Práticas Blade

- Use `{{ }}` para output escapado (padrão)
- Use `{!! !!}` apenas quando absolutamente necessário (HTML confiável)
- Use `@auth`, `@guest`, `@can` para verificações de autorização
- Componentes reutilizáveis para elementos repetidos

### JavaScript

#### Nomenclatura

- **Variáveis e funções**: camelCase (`getUserData`, `totalItems`)
- **Classes**: PascalCase (`ImageUploader`, `FormValidator`)
- **Constantes**: UPPER_SNAKE_CASE (`MAX_FILE_SIZE`)

#### Boas Práticas JS

```javascript
// ✅ BOM
const loadImoveis = async (filtros = {}) => {
    try {
        const response = await fetch('/api/imoveis', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(filtros),
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Erro ao carregar imóveis:', error);
        throw error;
    }
};

// ❌ EVITAR
function loadImoveis(filtros) {
    $.post('/api/imoveis', filtros, function(data) {
        console.log(data);
    });
}
```

### CSS/SASS

#### Nomenclatura (BEM)

```scss
// Bloco
.imovel-card {
    padding: 1rem;
    
    // Elemento
    &__titulo {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    &__descricao {
        color: #666;
    }
    
    // Modificador
    &--destaque {
        border: 2px solid #007bff;
    }
}
```

#### Boas Práticas CSS

- Use variáveis SASS para cores, espaçamentos
- Evite `!important` (use especificidade correta)
- Mobile-first approach
- Organize por componentes

---

## 📝 Padrões de Commit

Seguimos o [Conventional Commits](https://www.conventionalcommits.org/).

### Formato

```
<tipo>(<escopo>): <descrição>

[corpo opcional]

[rodapé opcional]
```

### Tipos

- `feat`: Nova funcionalidade
- `fix`: Correção de bug
- `docs`: Apenas documentação
- `style`: Formatação, ponto e vírgula faltando, etc
- `refactor`: Refatoração de código
- `perf`: Melhoria de performance
- `test`: Adição ou correção de testes
- `chore`: Tarefas de build, configurações, etc

### Exemplos

```bash
feat(imoveis): adiciona filtro por data de cadastro

Implementa novo filtro na listagem de imóveis que permite
buscar por data de cadastro específica ou faixa de datas.

Closes #123
```

```bash
fix(upload): corrige erro ao fazer upload de múltiplas imagens

O sistema estava quebrando ao tentar fazer upload de mais de 5 imagens
simultaneamente. Ajustado o limite e adicionado validação no frontend.
```

```bash
docs: atualiza README com instruções de deploy
```

---

## 🔄 Processo de Pull Request

### Checklist Antes de Submeter

- [ ] Código segue os padrões do projeto
- [ ] Comentários e documentação adicionados/atualizados
- [ ] Testes passam localmente
- [ ] Sem conflitos com a branch principal
- [ ] Commits seguem o padrão Conventional Commits
- [ ] PR tem descrição clara do que foi feito

### Template de Pull Request

```markdown
## Descrição

Breve descrição das mudanças realizadas.

## Tipo de Mudança

- [ ] Bug fix (correção de bug)
- [ ] Nova funcionalidade (feature)
- [ ] Breaking change (mudança que quebra compatibilidade)
- [ ] Documentação

## Como Testar

1. Passo 1
2. Passo 2
3. Resultado esperado

## Screenshots (se aplicável)

Cole screenshots aqui

## Checklist

- [ ] Meu código segue os padrões do projeto
- [ ] Revisei meu próprio código
- [ ] Comentei partes complexas do código
- [ ] Atualizei a documentação
- [ ] Minhas mudanças não geram novos warnings
- [ ] Adicionei testes que provam que minha correção funciona
- [ ] Testes novos e existentes passam localmente
```

### Processo de Revisão

1. Pelo menos um maintainer deve revisar
2. Todas as discussões devem ser resolvidas
3. CI/CD deve passar (quando implementado)
4. Aprovação necessária antes do merge

---

## 🐛 Reportando Bugs

### Antes de Reportar

1. Verifique se já não existe uma issue aberta
2. Teste na versão mais recente
3. Colete informações sobre o ambiente

### Template de Bug Report

```markdown
**Descrição do Bug**
Descrição clara e concisa do bug.

**Passos para Reproduzir**
1. Vá para '...'
2. Clique em '...'
3. Role até '...'
4. Veja o erro

**Comportamento Esperado**
Descrição do que deveria acontecer.

**Screenshots**
Se aplicável, adicione screenshots.

**Ambiente:**
- OS: [ex: Windows 10, Ubuntu 20.04]
- Browser: [ex: Chrome 91, Firefox 89]
- Versão do PHP: [ex: 8.1.5]
- Versão do Laravel: [ex: 10.10]

**Informações Adicionais**
Qualquer contexto adicional sobre o problema.
```

---

## 💡 Sugerindo Melhorias

### Template de Feature Request

```markdown
**A sua feature está relacionada a um problema? Descreva.**
Descrição clara do problema. Ex: "Sempre me incomoda quando [...]"

**Descreva a solução que você gostaria**
Descrição clara da solução desejada.

**Descreva alternativas que você considerou**
Descrição de soluções alternativas.

**Contexto Adicional**
Qualquer outro contexto, screenshots sobre a feature.
```

---

## 🧪 Testes

### Executando Testes

```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test --filter NomeDoTeste

# Com coverage
php artisan test --coverage
```

### Escrevendo Testes

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Imovel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImovelTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_criar_imovel()
    {
        $user = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)
            ->post('/admin/imoveis', [
                'titulo' => 'Casa em Teste',
                'tipo_negocio' => 'venda',
                'valor' => 300000,
                // ... outros campos
            ]);

        $response->assertRedirect('/admin/imoveis');
        $this->assertDatabaseHas('imoveis', [
            'titulo' => 'Casa em Teste',
        ]);
    }
}
```

---

## 📚 Recursos Úteis

- [Documentação do Laravel](https://laravel.com/docs)
- [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [Git Flow](https://nvie.com/posts/a-successful-git-branching-model/)

---

## ❓ Dúvidas

Se tiver alguma dúvida, abra uma issue com a tag `question` ou entre em contato:

- Email: dev@umuprime.com
- Discord: [Link do servidor]

---

**Obrigado por contribuir! 🎉**

# POKÉDEX CUSTOM EDITION

![Captura de tela](.github/screenshot.png)

**SENAI Limeira · Técnico em Desenvolvimento de Sistemas**
Laravel 13 · PokéAPI · MySQL · Pokémons gerados por Nano Banana 2

---

## Sobre o Projeto

Pokédex completa com duas seções integradas: a **Pokédex oficial** (dados da PokéAPI) e o **Custom DEX** (Pokémons originais criados com IA). Inclui sistema de quiz, gerenciamento de time, cry com visualizador de onda e upload de sprites.

---

## Funcionalidades

| Módulo | Recurso |
| ------ | ------- |
| **Pokédex** | Busca por nome ou número, seletor de geração (1–9), sprite normal/shiny |
| **Pokédex** | Stats, fraquezas, cadeia evolutiva, movimentos, flavor text |
| **Pokédex** | Cry com áudio oficial + visualizador de onda em tempo real |
| **Time** | Montar time de até 6 Pokémons, persistido em sessão |
| **Quiz** | Adivinhe o Pokémon pela silhueta, streak, precisão, dificuldade por geração |
| **Custom DEX** | Listagem, detalhes, criação com upload de sprite |
| **Custom DEX** | Ataques customizados (até 4 por Pokémon) |
| **Custom DEX** | Cry procedural gerado via Web Audio API (único por Pokémon) |
| **Custom Quiz** | Quiz de silhueta para os Pokémons criados |

---

## Stack

- **Backend:** Laravel 13 (PHP 8.2+)
- **Banco de dados:** MySQL / MariaDB
- **Frontend:** Blade + Tailwind CSS (CDN) + Press Start 2P (Google Fonts)
- **API externa:** [PokéAPI v2](https://pokeapi.co/)
- **Áudio:** Web Audio API (cry procedural no Custom DEX; áudio oficial na Pokédex)
- **Sprites IA:** Gemini Imagen (Google)

---

## Setup Local

### 1. Clonar e instalar dependências

```bash
git clone <repo-url>
cd pokedex
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configurar banco de dados

Edite `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pokedex
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 3. Migrations e seed

```bash
php artisan migrate
php artisan db:seed --class=CustomPokemonSeeder
```

### 4. Link de storage (para upload de sprites)

```bash
php artisan storage:link
```

### 5. Iniciar servidor

```bash
php artisan serve
```

Acesse: `http://localhost:8000`

---

## Rotas

| Método | URL | Descrição |
|--------|-----|-----------|
| GET | `/` | Pokédex principal |
| GET | `/quiz` | Quiz de silhueta (PokéAPI) |
| POST | `/quiz` | Enviar resposta do quiz |
| GET | `/custom-pokemons` | Listagem do Custom DEX |
| GET | `/custom-pokemons/criar` | Formulário de criação |
| POST | `/custom-pokemons` | Salvar novo Pokémon |
| GET | `/custom-pokemons/quiz` | Quiz do Custom DEX |
| POST | `/custom-pokemons/quiz` | Enviar resposta do quiz custom |
| GET | `/custom-pokemons/{id}` | Detalhes de um Pokémon custom |
| POST | `/team/add` | Adicionar ao time |
| POST | `/team/remove` | Remover do time |

---

## Estrutura do Banco — `custom_pokemons`

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| `id` | BIGINT PK | Chave primária |
| `dex_number` | INT único | Número na Pokédex |
| `name` | VARCHAR | Nome do Pokémon |
| `type_primary` | VARCHAR | Tipo principal |
| `type_secondary` | VARCHAR null | Tipo secundário |
| `base_animal` | VARCHAR | Animal inspirador |
| `inspiration` | TEXT | Tema / linguagem |
| `hp` | INT | Stat HP (1–255) |
| `attack` | INT | Stat Ataque (1–255) |
| `defense` | INT | Stat Defesa (1–255) |
| `speed` | INT | Stat Velocidade (1–255) |
| `sprite_path` | VARCHAR null | Caminho da imagem (storage) |
| `attacks` | JSON null | Array de até 4 ataques |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |

---

## Custom Pokémons

Três Pokémons originais com tema de linguagens de programação, sprites gerados pelo **Gemini Imagen**.

---

### #6388 — JAVEER

![Javeer — Cervo Circuito](public/sprites/javeer.png)

> *"Seu ódio pelo JavaScript é tão intenso que os chifres disparam raios espontâneos quando detecta código `.js` nas proximidades."*

| Campo | Detalhe |
|-------|---------|
| **Categoria** | Cervo Circuito |
| **Animal base** | Cervo de Nara |
| **Tipo** | Electric / Steel |
| **Inspiração** | Linguagem Java |

| Stat | Valor |
|------|------:|
| HP | 70 |
| Ataque | 85 |
| Defesa | 80 |
| Velocidade | 65 |
| **Total** | **300** |

| Golpe | Descrição |
|-------|-----------|
| **ByteStrike** | Choque elétrico pelos chifres que compila o inimigo no lugar. |
| **GarbageCollect** | Remove todos os buffs do inimigo — limpa a memória do campo. |
| **NullPointer** | Ataque psíquico que deixa o inimigo em confusão total. |
| **JVM Crash** | Carregado por 3 turnos; causa dano devastador ao executar. |

---

### #6389 — ARTISAUR

![Artisaur — Furão Framework](public/sprites/artisaur.png)

> *"Carrega os símbolos sagrados do Laravel, PHP e Artisan. Executa comandos mágicos com um simples toque das patas."*

| Campo | Detalhe |
|-------|---------|
| **Categoria** | Furão Framework |
| **Animal base** | Furão |
| **Tipo** | Grass / Psychic |
| **Inspiração** | PHP + Laravel Artisan |

| Stat | Valor |
|------|------:|
| HP | 65 |
| Ataque | 70 |
| Defesa | 55 |
| Velocidade | 90 |
| **Total** | **280** |

| Golpe | Descrição |
|-------|-----------|
| **Artisan Slash** | Garra verde que causa dano e reduz a velocidade do alvo. |
| **Migration Wave** | Altera o campo e muda os tipos dos Pokémons inimigos. |
| **Blade Cut** | Corte psíquico que ignora metade da resistência do alvo. |
| **Composer Install** | Cura o time aliado — mas o Artisaur fica parado por 2 turnos. |

---

### #6390 — PYRANIX

![Pyranix — Lobo Script](public/sprites/pyranix.png)

> *"Rodeado por serpentes de dados e comandos `import` flutuantes. O símbolo do Python brilha sobre sua cabeça ao usar ataques especiais."*

| Campo | Detalhe |
|-------|---------|
| **Categoria** | Lobo Script |
| **Animal base** | Lobo |
| **Tipo** | Water / Dark |
| **Inspiração** | Python |

| Stat | Valor |
|------|------:|
| HP | 75 |
| Ataque | 90 |
| Defesa | 60 |
| Velocidade | 95 |
| **Total** | **320** |

| Golpe | Descrição |
|-------|-----------|
| **Serpent Import** | Invoca cobra de dados que envenena o inimigo a cada turno. |
| **IndentError** | Paralisa o inimigo por erro de estrutura — 2 turnos sem agir. |
| **PipInstall** | Absorve força do inimigo e transfere para o Pyranix. |
| **Lambda Fang** | Sempre ataca primeiro, independente da velocidade. |

---

## Comparativo

| Pokémon | HP | Ataque | Defesa | Velocidade | Total |
| ------- | -- | ------ | ------ | ---------- | ----- |
| Javeer (Electric/Steel) | 70 | 85 | 80 | 65 | **300** |
| Artisaur (Grass/Psychic) | 65 | 70 | 55 | 90 | **280** |
| Pyranix (Water/Dark) | 75 | 90 | 60 | 95 | **320** |

---

*Sprites gerados por Gemini Nano Banana 2 (Google) · Laravel 13 + PokéAPI v2*
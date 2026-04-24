# Pokédex — Laravel + PokéAPI

Uma aplicação web construída com Laravel que consome a [PokéAPI](https://pokeapi.co) e exibe as informações de Pokémon em uma interface visual inspirada na Pokédex dos jogos clássicos.

## Funcionalidades

- Busca de Pokémon por **nome** ou **número** da Pokédex
- Exibição de sprite oficial, tipos, altura, peso e stats base
- Botão para buscar um Pokémon **aleatório** (geração 1, números 1–151)
- Interface estilo **Pokédex retrô** com fonte pixel art e tela estilo Game Boy
- Tratamento de erros para Pokémon não encontrados

## Tecnologias

- **PHP 8+** / **Laravel 11**
- **Blade** (template engine)
- **Tailwind CSS** (via CDN)
- **PokéAPI** — API pública e gratuita de Pokémon
- **Press Start 2P** — fonte pixel art (Google Fonts)

## Como rodar localmente

**1. Clone o repositório**
```bash
git clone https://github.com/jggoncalez/modelo_api_ta.git
cd modelo_api_ta
```

**2. Instale as dependências**
```bash
composer install
```

**3. Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Inicie o servidor**
```bash
php artisan serve
```

Acesse [http://localhost:8000/pokemon](http://localhost:8000/pokemon) no navegador.

## Estrutura relevante

```
app/Http/Controllers/PokemonController.php   # Lógica de busca na PokéAPI
resources/views/pokemon.blade.php            # View da Pokédex
routes/web.php                               # Rota /pokemon
```

## Como usar

- Acesse `/pokemon` para carregar um Pokémon aleatório
- Digite o nome (ex: `pikachu`) ou número (ex: `25`) no campo de busca e clique em **OK**
- Clique em **RAND** para buscar um Pokémon aleatório

## Licença

Este projeto está licenciado sob a [MIT License](https://opensource.org/licenses/MIT).

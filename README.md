# Assadão do Zé 🔥

Site dinâmico em PHP para uma churrascaria fictícia, com cardápio, serviços,
calculadora de orçamento e formulário de contato — agora conectado a um
banco de dados MySQL real. Tema visual: **laranja**.

## Estrutura de pastas

```
assadao-do-ze/
├── database/
│   ├── banco.sql                  -> script SQL: tabelas + dados iniciais
│   ├── DER.md                     -> Diagrama de Entidade Relacionamento (abra este no GitHub)
│   └── der-assadao-do-ze.mermaid  -> mesmo diagrama, formato bruto (para outras ferramentas)
├── config/
│   ├── conexao.php                -> conexão PDO com o MySQL
│   └── dados.php                  -> consultas (SELECT) ao banco
├── includes/
│   ├── cabecalho.php              -> template do topo (menu, head)
│   ├── rodape.php                 -> template do rodapé
│   └── funcoes.php                -> funções de processamento/validação/filtro
├── assets/
│   └── css/estilo.css             -> tema laranja
├── index.php                      -> Início
├── cardapio.php                   -> Cardápio (filtro + pesquisa)
├── servicos.php                   -> Serviços + calculadora de orçamento
├── sobre.php                      -> Sobre a empresa + FAQ
├── contato.php                    -> Formulário de contato (grava no banco)
├── .htaccess                      -> desabilita listagem de diretórios
└── README.md
```

## Como colocar o banco de dados no ar

1. Crie o banco e as tabelas importando o script `database/banco.sql`:

   ```bash
   mysql -u root -p < database/banco.sql
   ```

   Ou, se preferir o phpMyAdmin: abra o phpMyAdmin, vá em **Importar** e
   selecione o arquivo `database/banco.sql`. Ele já cria o banco
   `assadao_do_ze`, todas as tabelas e insere os produtos/serviços iniciais.

2. Ajuste as credenciais em `config/conexao.php` caso seu MySQL use usuário/senha
   diferentes do padrão (`root` sem senha):

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NOME', 'assadao_do_ze');
   define('DB_USUARIO', 'root');
   define('DB_SENHA', '');
   ```

## Como rodar o site localmente

Com o PHP instalado (com a extensão `pdo_mysql` habilitada), dentro da
pasta do projeto:

```bash
php -S localhost:8080
```

Depois é só acessar `http://localhost:8080` no navegador.

## Modelagem do banco de dados

O banco `assadao_do_ze` tem 7 tabelas:

| Tabela | Descrição |
|---|---|
| `categorias` | Categorias do cardápio (bovina, suína, aves, etc.) |
| `produtos` | Produtos vendidos, cada um ligado a uma categoria |
| `servicos` | Serviços oferecidos (buffet, churrasco em domicílio, etc.) |
| `clientes` | Clientes que fazem pedidos |
| `pedidos` | Pedidos feitos pelos clientes |
| `pedido_produtos` | Tabela associativa entre pedidos e produtos (**N:N**) |
| `mensagens_contato` | Mensagens enviadas pelo formulário de contato |

**Chaves estrangeiras:**
- `produtos.categoria_id` → `categorias.id`
- `pedidos.cliente_id` → `clientes.id`
- `pedido_produtos.pedido_id` → `pedidos.id`
- `pedido_produtos.produto_id` → `produtos.id`

**Relacionamento N:N:** um pedido pode conter vários produtos, e um
mesmo produto pode aparecer em vários pedidos — esse relacionamento é
resolvido pela tabela associativa `pedido_produtos`, cuja chave primária
é composta por `(pedido_id, produto_id)`.

O diagrama completo está em [`database/DER.md`](database/DER.md) — abra esse arquivo
(não o `.mermaid`) quando for mostrar o DER pelo GitHub: o GitHub só desenha o
diagrama quando o código Mermaid está dentro de um bloco ```mermaid``` em um
arquivo `.md`; um arquivo `.mermaid` sozinho aparece como texto puro.

## Itens de infraestrutura (Sistemas Operacionais e Redes)

Esses itens da rubrica dependem de configuração de servidor, não apenas de
código PHP. Seguem as instruções para quando for colocar no ar:

- **Proibir listagem de diretórios**: já resolvido pelo arquivo `.htaccess`
  (`Options -Indexes`).
- **Aplicação rodando na porta 8080**: se usar Apache, crie um
  VirtualHost como abaixo (arquivo `/etc/apache2/ports.conf` e
  `sites-available`):

  ```apache
  Listen 8080

  <VirtualHost *:8080>
      DocumentRoot /var/www/assadao-do-ze
      ServerName assadaodoze.local
      <Directory /var/www/assadao-do-ze>
          AllowOverride All
          Require all granted
      </Directory>
  </VirtualHost>
  ```

  Ou, para testes rápidos com o servidor embutido do PHP:
  `php -S 0.0.0.0:8080`

- **Configurar DNS local**: adicione uma linha no arquivo de hosts da
  máquina apontando para o IP do servidor:

  ```
  # Linux/Mac: /etc/hosts | Windows: C:\Windows\System32\drivers\etc\hosts
  192.168.0.10   assadaodoze.local
  ```

- **Banco de dados com IP fixo / aplicação e banco em máquinas
  separadas**: se o MySQL rodar em outra máquina/VM, dê um IP fixo a ela
  (ex.: `192.168.0.50`) e troque `DB_HOST` em `config/conexao.php` para
  esse endereço, em vez de `localhost`.

## Mapeamento com a rubrica

| Disciplina | Itens cobertos no código |
|---|---|
| Modelagem e Banco de Dados | DER em `database/DER.md`; 7 tabelas (mínimo 3); chave primária em todas as tabelas; chaves estrangeiras (`produtos.categoria_id`, `pedidos.cliente_id`, `pedido_produtos.pedido_id/produto_id`); relacionamento N:N entre `pedidos` e `produtos` via `pedido_produtos` |
| Sistemas Operacionais e Redes | Proibição de listagem de diretórios (`.htaccess`); host do banco configurável para IP fixo (`config/conexao.php`) |
| Desenvolvimento Web Moderna | Layout dinâmico com PHP; Template com PHP (`cabecalho.php`/`rodape.php`); Bootstrap com Navbar, Carousel, Cards, Modal, Accordion e Alert (mais de 3 componentes); conexão real com banco de dados via PDO (`config/conexao.php`); dados recuperados do banco e exibidos na tela (`config/dados.php`); uso de IF, WHILE e FOREACH |
| Tech Forge | Funções modularizadas (`includes/funcoes.php`); fluxo de dados via parâmetros e retorno; lógica de pesquisa/filtro no cardápio; validação de regras de negócio (calculadora de orçamento e formulário de contato, agora gravado na tabela `mensagens_contato`) |

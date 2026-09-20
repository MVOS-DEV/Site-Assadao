# Diagrama de Entidade Relacionamento - Assadão do Zé

Este diagrama representa a modelagem do banco `assadao_do_ze` (script completo em [`banco.sql`](./banco.sql)).

```mermaid
erDiagram
    CATEGORIAS ||--o{ PRODUTOS : classifica
    CLIENTES ||--o{ PEDIDOS : realiza
    PEDIDOS ||--o{ PEDIDO_PRODUTOS : contem
    PRODUTOS ||--o{ PEDIDO_PRODUTOS : "está em"

    CATEGORIAS {
        int id PK
        varchar nome
        varchar slug
    }

    PRODUTOS {
        int id PK
        int categoria_id FK
        varchar nome
        text descricao
        decimal preco
        varchar unidade
        int estoque
    }

    SERVICOS {
        int id PK
        varchar nome
        text descricao
        decimal preco_kg
        varchar icone
    }

    CLIENTES {
        int id PK
        varchar nome
        varchar email
        varchar telefone
    }

    PEDIDOS {
        int id PK
        int cliente_id FK
        datetime data_pedido
        varchar status
        text observacoes
    }

    PEDIDO_PRODUTOS {
        int pedido_id PK_FK
        int produto_id PK_FK
        decimal quantidade
        decimal preco_unitario
    }

    MENSAGENS_CONTATO {
        int id PK
        varchar nome
        varchar email
        varchar telefone
        varchar assunto
        text mensagem
    }
```

**Chaves estrangeiras:** `produtos.categoria_id → categorias.id`, `pedidos.cliente_id → clientes.id`, `pedido_produtos.pedido_id → pedidos.id`, `pedido_produtos.produto_id → produtos.id`.

**Relacionamento N:N:** `PEDIDOS` ↔ `PRODUTOS`, resolvido pela tabela associativa `PEDIDO_PRODUTOS` (chave primária composta).
